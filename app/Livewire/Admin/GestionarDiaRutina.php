<?php

namespace App\Livewire\Admin;

use App\Models\RutinaDia;
use App\Models\Ejercicio;
use App\Models\RutinaEjercicio;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

/**
 * Componente para gestionar los ejercicios de un día específico.
 * Permite buscar ejercicios, añadirlos al día y editar sus detalles.
 */
#[Layout('layouts.app')]
class GestionarDiaRutina extends Component
{
    public RutinaDia $dia;
    
    // Buscador
    public $search = '';
    
    // Edición en línea (array indexado por rutina_ejercicio_id)
    public $ejerciciosData = [];

    public function mount($diaId)
    {
        $this->dia = RutinaDia::with(['rutina.atleta', 'rutinaEjercicios.ejercicio'])->findOrFail($diaId);
        $this->authorize('view', $this->dia->rutina);
        
        // Inicializar datos de edición
        $this->refreshEjerciciosData();
    }

    public function refreshEjerciciosData()
    {
        $this->ejerciciosData = [];
        foreach ($this->dia->rutinaEjercicios as $re) {
            $this->ejerciciosData[$re->id] = [
                'series' => $re->series,
                'repeticiones' => $re->repeticiones,
                'peso_sugerido' => $re->peso_sugerido,
                'unidad_peso' => $re->unidad_peso ?? 'kg',
                'descanso_segundos' => $re->descanso_segundos,
                'indicaciones' => $re->indicaciones,
                'tempo' => $re->tempo ?? [
                    'fase1' => ['accion' => 'Bajar', 'tiempo' => ''],
                    'fase2' => ['accion' => 'Mantener', 'tiempo' => ''],
                    'fase3' => ['accion' => 'Subir', 'tiempo' => ''],
                ],
                'has_tempo' => !empty($re->tempo),
            ];
        }
    }

    #[Computed]
    public function searchResults()
    {
        if (strlen($this->search) < 2) {
            return [];
        }

        return Ejercicio::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('descripcion', 'like', '%' . $this->search . '%')
            ->take(10)
            ->get();
    }

    public function addEjercicio($ejercicioId)
    {
        $ejercicio = Ejercicio::findOrFail($ejercicioId);
        
        // Calcular orden
        $maxOrden = $this->dia->rutinaEjercicios()->max('orden_en_dia') ?? 0;

        $nuevo = RutinaEjercicio::create([
            'rutina_dia_id' => $this->dia->id,
            'ejercicio_id' => $ejercicio->id,
            'series' => 3, // Default
            'repeticiones' => '10-12', // Default
            'orden_en_dia' => $maxOrden + 1,
        ]);

        $this->dia->refresh();
        $this->refreshEjerciciosData();
        $this->search = ''; // Limpiar búsqueda
        
        $this->dispatch('notify', message: 'Ejercicio añadido', type: 'success');
    }

    public function removeEjercicio($rutinaEjercicioId)
    {
        $re = RutinaEjercicio::where('rutina_dia_id', $this->dia->id)->findOrFail($rutinaEjercicioId);
        $re->delete();
        
        $this->dia->refresh();
        $this->refreshEjerciciosData();
        
        $this->dispatch('notify', message: 'Ejercicio eliminado', type: 'success');
    }

    // Guardado automático al perder foco (blur)
    public function updateEjercicio($rutinaEjercicioId, $field, $value)
    {
        $re = RutinaEjercicio::where('rutina_dia_id', $this->dia->id)->findOrFail($rutinaEjercicioId);
        
        // Manejo especial para campos anidados de tempo (ej: tempo.fase1.tiempo)
        if (str_starts_with($field, 'tempo.')) {
            $parts = explode('.', $field); // tempo, fase1, tiempo
            $tempoData = $this->ejerciciosData[$rutinaEjercicioId]['tempo'];
            
            // Actualizar el valor en el array local
            if (count($parts) === 3) {
                $tempoData[$parts[1]][$parts[2]] = $value;
            }
            
            // Guardar en BD
            $re->update(['tempo' => $tempoData]);
            $this->ejerciciosData[$rutinaEjercicioId]['tempo'] = $tempoData;
            return;
        }

        // Manejo del toggle de tempo
        if ($field === 'has_tempo') {
            $this->ejerciciosData[$rutinaEjercicioId]['has_tempo'] = $value;
            if (!$value) {
                $re->update(['tempo' => null]);
                $this->ejerciciosData[$rutinaEjercicioId]['tempo'] = [
                    'fase1' => ['accion' => 'Bajar', 'tiempo' => ''],
                    'fase2' => ['accion' => 'Mantener', 'tiempo' => ''],
                    'fase3' => ['accion' => 'Subir', 'tiempo' => ''],
                ];
            } else {
                 // Si se activa y estaba null, guardar el default
                 $re->update(['tempo' => $this->ejerciciosData[$rutinaEjercicioId]['tempo']]);
            }
            return;
        }
        
        $re->update([$field => $value]);
        
        // Actualizar estado local
        $this->ejerciciosData[$rutinaEjercicioId][$field] = $value;
    }

    // Edición de Nombre del Día
    public function updateNombreDia($nuevoNombre)
    {
        $this->dia->update(['nombre_dia' => $nuevoNombre]);
        $this->dispatch('notify', message: 'Nombre del día actualizado', type: 'success');
    }

    // Creación de Ejercicio
    public $showCreateEjercicioModal = false;
    public $newEjercicioNombre = '';
    public $newEjercicioGrupoMuscularId = '';

    public function openCreateEjercicioModal()
    {
        $this->reset(['newEjercicioNombre', 'newEjercicioGrupoMuscularId']);
        $this->showCreateEjercicioModal = true;
    }

    public function createEjercicio()
    {
        $this->validate([
            'newEjercicioNombre' => 'required|min:3|max:100|unique:ejercicios,nombre',
            'newEjercicioGrupoMuscularId' => 'required|exists:grupos_musculares,id',
        ]);

        $ejercicio = \App\Models\Ejercicio::create([
            'nombre' => $this->newEjercicioNombre,
            'grupo_muscular_id' => $this->newEjercicioGrupoMuscularId,
            'descripcion' => 'Creado desde el editor de rutina',
        ]);

        $this->showCreateEjercicioModal = false;
        $this->dispatch('notify', message: 'Ejercicio creado correctamente', type: 'success');
        
        // Opcional: Añadirlo automáticamente al día o buscarlo
        $this->search = $ejercicio->nombre; // Para que aparezca en el buscador
    }

    public function render()
    {
        return view('livewire.admin.gestionar-dia-rutina', [
            'gruposMusculares' => \App\Models\GrupoMuscular::orderBy('nombre')->get(),
        ]);
    }
}
