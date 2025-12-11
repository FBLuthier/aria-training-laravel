<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseCrudComponent;
// Asumiremos que usaremos un Form object o validaremos inline por simplicidad inicial
use App\Models\Ejercicio;
use App\Models\Equipo;
use App\Models\GrupoMuscular;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

/**
 * Componente para gestionar Ejercicios.
 *
 * Implementa la lógica de "Creación Masiva" acordada:
 * - Al crear: Se permite seleccionar múltiples equipos. Se crea un ejercicio por cada equipo.
 * - Al editar: Se edita un solo ejercicio (para mantener integridad de IDs).
 */
#[Layout('layouts.app')]
class GestionarEjercicios extends BaseCrudComponent
{
    // =======================================================================
    //  PROPIEDADES
    // =======================================================================

    // Propiedades del Formulario
    #[Rule('required|min:3|max:255')]
    public $nombre = '';

    #[Rule('nullable|string')]
    public $descripcion = '';

    #[Rule('nullable|url|max:255')]
    public $url_video = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'; // Valor por defecto

    #[Rule('required|exists:grupos_musculares,id')]
    public $grupo_muscular_id = '';

    // Para Creación (Múltiple)
    #[Rule('required|array|min:1')]
    public $equipos_seleccionados = [];

    public $equipos_urls = []; // Array para URLs específicas por equipo

    public $equipos_descripciones = []; // Array para descripciones específicas por equipo

    // Para Edición (Único)
    #[Rule('required|exists:equipos,id')]
    public $equipo_id = '';

    public ?int $editingId = null; // ID del ejercicio que se está editando

    public $is_bulk_create = true; // Flag para saber si estamos en modo creación masiva

    // Colecciones para los Selects
    public $equipos_list = [];

    public $grupos_musculares_list = [];

    // =======================================================================
    //  MÉTODOS BASE (Sobrescritos)
    // =======================================================================

    protected function getModelClass(): string
    {
        return Ejercicio::class;
    }

    #[Computed]
    public function items()
    {
        return Ejercicio::forUser(auth()->user())
            ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
            ->paginate($this->getPerPage());
    }

    protected function getViewName(): string
    {
        return 'livewire.admin.gestionar-ejercicios';
    }

    // =======================================================================
    //  CICLO DE VIDA
    // =======================================================================

    public function mount()
    {
        $this->equipos_list = Equipo::forUser(auth()->user())->orderBy('nombre')->get();
        $this->grupos_musculares_list = GrupoMuscular::orderBy('nombre')->get();
    }

    #[Computed]
    public function videoEmbedUrl(): ?string
    {
        // Si está vacío, usar el video por defecto
        $url = empty($this->url_video) ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : $this->url_video;

        // Patrón simple para extraer ID de YouTube (soporta youtu.be y youtube.com)
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);

        return isset($matches[1]) ? 'https://www.youtube.com/embed/'.$matches[1] : null;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->reset(['nombre', 'descripcion', 'grupo_muscular_id', 'equipos_seleccionados', 'equipo_id', 'editingId', 'is_bulk_create', 'equipos_urls', 'equipos_descripciones']);
        $this->url_video = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'; // Reset al default
        $this->resetValidation();
    }

    public function updatedShowFormModal($value): void
    {
        if (! $value) {
            $this->reset(['nombre', 'descripcion', 'grupo_muscular_id', 'equipos_seleccionados', 'equipo_id', 'editingId', 'is_bulk_create', 'equipos_urls', 'equipos_descripciones']);
            $this->url_video = 'https://youtu.be/TOOb6fSvlnM'; // Reset al default
            $this->resetValidation();
        }
    }

    public function create(): void
    {
        $this->reset(['nombre', 'descripcion', 'grupo_muscular_id', 'equipos_seleccionados', 'equipo_id', 'equipos_urls', 'equipos_descripciones']);
        $this->url_video = 'https://youtu.be/TOOb6fSvlnM'; // Default
        $this->is_bulk_create = true;
        $this->showFormModal = true;
    }

    public function edit(int $id): void
    {
        $this->reset(['nombre', 'descripcion', 'grupo_muscular_id', 'equipos_seleccionados', 'equipo_id', 'equipos_urls', 'equipos_descripciones']);
        $this->url_video = 'https://youtu.be/TOOb6fSvlnM'; // Default inicial
        $this->is_bulk_create = false;

        $model = Ejercicio::findOrFail($id);

        $this->nombre = $model->nombre;
        if ($model->equipo) {
            $this->nombre = str_replace(' ('.$model->equipo->nombre.')', '', $this->nombre);
        }

        $this->descripcion = $model->descripcion;
        $this->url_video = $model->url_video; // Cargar video
        $this->grupo_muscular_id = $model->grupo_muscular_id;
        $this->equipo_id = $model->equipo_id;

        $this->editingId = $id;

        // Autorización: Verificar si puede editar este ejercicio específico
        $this->authorize('update', $model);

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $rules = [
            'nombre' => 'required|min:3|max:255',
            'grupo_muscular_id' => 'required|exists:grupos_musculares,id',
            'descripcion' => 'nullable|string',
            'url_video' => 'nullable|url|max:255',
        ];

        if ($this->is_bulk_create) {
            $rules['equipos_seleccionados'] = 'required|array|min:1';
            // Validar URLs específicas si existen? Por ahora lo dejamos opcional/flexible
        } else {
            $rules['equipo_id'] = 'required|exists:equipos,id';
        }

        $this->validate($rules);

        if ($this->is_bulk_create) {
            $count = 0;
            foreach ($this->equipos_seleccionados as $eqId) {
                $equipo = Equipo::find($eqId);
                $nombreFinal = $this->nombre.' ('.$equipo->nombre.')';

                // Prioridad: URL específica > URL global > Default
                $videoFinal = $this->url_video;
                if (! empty($this->equipos_urls[$eqId])) {
                    $videoFinal = $this->equipos_urls[$eqId];
                }

                // Prioridad: Descripción específica > Descripción global
                $descripcionFinal = $this->descripcion;
                if (! empty($this->equipos_descripciones[$eqId])) {
                    $descripcionFinal = $this->equipos_descripciones[$eqId];
                }

                $ejercicio = Ejercicio::create([
                    'nombre' => $nombreFinal,
                    'descripcion' => $descripcionFinal,
                    'url_video' => $videoFinal,
                    'grupo_muscular_id' => $this->grupo_muscular_id,
                    'equipo_id' => $eqId,
                    'estado' => 1,
                    'usuario_id' => auth()->id(), // Asignar creador
                ]);

                // AUDITORÍA: Registrar creación
                $this->auditCreate($ejercicio);

                $count++;
            }
            $this->dispatch('notify', message: "$count ejercicios creados correctamente", type: 'success');
        } else {
            // ... (lógica de edición individual sin cambios)
            $model = Ejercicio::findOrFail($this->editingId);
            $equipo = Equipo::find($this->equipo_id);

            // Capturar valores anteriores para auditoría
            $oldValues = $model->toArray();

            $nombreFinal = $this->nombre;
            if (! str_contains($nombreFinal, '('.$equipo->nombre.')')) {
                $nombreFinal = $this->nombre.' ('.$equipo->nombre.')';
            }

            $model->update([
                'nombre' => $nombreFinal,
                'descripcion' => $this->descripcion,
                'url_video' => $this->url_video,
                'grupo_muscular_id' => $this->grupo_muscular_id,
                'equipo_id' => $this->equipo_id,
            ]);

            // AUDITORÍA: Registrar actualización
            $this->auditUpdate($model, $oldValues);

            $this->dispatch('notify', message: 'Ejercicio actualizado correctamente', type: 'success');
        }

        $this->showFormModal = false;
        $this->reset(['nombre', 'descripcion', 'url_video', 'grupo_muscular_id', 'equipos_seleccionados', 'equipo_id', 'editingId', 'equipos_urls']);
    }
}
