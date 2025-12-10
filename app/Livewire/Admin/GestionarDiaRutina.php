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

    public $bloques = []; // Colección de bloques
    public $selectedBloqueId = ''; // Bloque seleccionado para añadir ejercicios

    public function mount($diaId)
    {
        $this->dia = RutinaDia::with(['rutina.atleta', 'bloques.rutinaEjercicios.ejercicio', 'rutinaEjercicios.ejercicio'])
            ->findOrFail($diaId);
        $this->authorize('view', $this->dia->rutina);
        
        // Inicializar datos de edición
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->dia->refresh();
        $this->bloques = $this->dia->bloques;
        
        $this->ejerciciosData = [];
        
        // Cargar datos de ejercicios (tanto los que están en bloques como los sueltos)
        $todosEjercicios = $this->dia->rutinaEjercicios; // Esto trae todos por la relación hasMany directa
        
        foreach ($todosEjercicios as $re) {
            $this->ejerciciosData[$re->id] = [
                'series' => $re->series,
                'repeticiones' => $re->repeticiones,
                'peso_sugerido' => $re->peso_sugerido,
                'unidad_peso' => $re->unidad_peso ?? 'kg',
                'unidad_repeticiones' => $re->unidad_repeticiones ?? 'reps',
                'descanso_segundos' => $re->descanso_segundos,
                'indicaciones' => $re->indicaciones,
                'tempo' => $re->tempo ?? [
                    'fase1' => ['accion' => 'Bajar', 'tiempo' => ''],
                    'fase2' => ['accion' => 'Mantener', 'tiempo' => ''],
                    'fase3' => ['accion' => 'Subir', 'tiempo' => ''],
                ],
                'has_tempo' => !empty($re->tempo),
                'track_rpe' => (bool) $re->track_rpe,
                'track_rir' => (bool) $re->track_rir,
                'is_unilateral' => (bool) $re->is_unilateral,
            ];
        }
    }

    #[Computed]
    public function searchResults()
    {
        if (strlen($this->search) < 2) {
            return [];
        }

        return Ejercicio::whereRaw('nombre COLLATE utf8mb4_general_ci LIKE ?', ['%' . $this->search . '%'])
            ->orWhereRaw('descripcion COLLATE utf8mb4_general_ci LIKE ?', ['%' . $this->search . '%'])
            ->take(10)
            ->get();
    }

    // --- Gestión de Bloques ---

    public function createBloque()
    {
        $maxOrden = $this->dia->bloques()->max('orden') ?? 0;
        
        $this->dia->bloques()->create([
            'nombre' => 'Nuevo Bloque',
            'orden' => $maxOrden + 1,
        ]);

        $this->refreshData();
        $this->dispatch('notify', message: 'Bloque añadido', type: 'success');
    }

    public function updateBloqueNombre($bloqueId, $nombre)
    {
        $bloque = $this->dia->bloques()->findOrFail($bloqueId);
        $bloque->update(['nombre' => $nombre]);
        $this->dispatch('notify', message: 'Nombre del bloque actualizado', type: 'success');
    }

    public function deleteBloque($bloqueId)
    {
        $bloque = $this->dia->bloques()->findOrFail($bloqueId);
        // Los ejercicios pasarán a rutina_bloque_id = null gracias a onDelete('set null') en la migración
        // O podemos eliminarlos si el usuario prefiere. Por seguridad, mejor mantenerlos.
        $bloque->delete();
        
        $this->refreshData();
        $this->dispatch('notify', message: 'Bloque eliminado', type: 'success');
    }

    public function addEjercicio($ejercicioId, $bloqueId = null)
    {
        if (empty($bloqueId)) {
            $bloqueId = null;
        }
        $ejercicio = Ejercicio::findOrFail($ejercicioId);
        
        // Calcular orden
        // Si es en un bloque, orden dentro del bloque? O orden global?
        // Actualmente usamos orden_en_dia global.
        $maxOrden = $this->dia->rutinaEjercicios()->max('orden_en_dia') ?? 0;

        $nuevo = RutinaEjercicio::create([
            'rutina_dia_id' => $this->dia->id,
            'rutina_bloque_id' => $bloqueId, // Asignar al bloque si existe
            'ejercicio_id' => $ejercicio->id,
            'series' => 3, // Default
            'repeticiones' => '10-12', // Default
            'orden_en_dia' => $maxOrden + 1,
        ]);

        $this->refreshData();
        $this->search = ''; // Limpiar búsqueda
        
        $this->dispatch('notify', message: 'Ejercicio añadido', type: 'success');
        $this->dispatch('focus-search');
    }

    public function removeEjercicio($rutinaEjercicioId)
    {
        $re = RutinaEjercicio::where('rutina_dia_id', $this->dia->id)->findOrFail($rutinaEjercicioId);
        $re->delete();
        
        $this->refreshData();
        
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

        // Manejo de RPE/RIR (Mutuamente excluyentes)
        if ($field === 'track_rpe') {
            $this->ejerciciosData[$rutinaEjercicioId]['track_rpe'] = $value;
            $re->update(['track_rpe' => $value]);
            
            if ($value) {
                // Si activa RPE, desactivar RIR
                $re->update(['track_rir' => false]);
                $this->ejerciciosData[$rutinaEjercicioId]['track_rir'] = false;
            }
            return;
        }

        if ($field === 'track_rir') {
            $this->ejerciciosData[$rutinaEjercicioId]['track_rir'] = $value;
            $re->update(['track_rir' => $value]);
            
            if ($value) {
                // Si activa RIR, desactivar RPE
                $re->update(['track_rpe' => false]);
                $this->ejerciciosData[$rutinaEjercicioId]['track_rpe'] = false;
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

    // --- Reordenamiento (Drag & Drop) ---

    public function reorderBloques($items)
    {
        foreach ($items as $index => $id) {
            $this->dia->bloques()->where('id', $id)->update(['orden' => $index + 1]);
        }
        $this->refreshData();
    }

    public function reorderEjercicio($ejercicioId, $bloqueId, $newOrden)
    {
        $re = RutinaEjercicio::where('rutina_dia_id', $this->dia->id)->findOrFail($ejercicioId);
        
        // Convertir bloqueId vacío a null
        $bloqueId = empty($bloqueId) ? null : $bloqueId;

        // Actualizar bloque si cambió
        if ($re->rutina_bloque_id != $bloqueId) {
            $re->rutina_bloque_id = $bloqueId;
        }

        // Estrategia de reordenamiento:
        // 1. "Hacer hueco" en la nueva posición
        // 2. Mover el elemento
        // 3. Re-indexar todo el grupo para asegurar consistencia (más costoso pero seguro)
        
        // Opción segura: Re-indexar todo el grupo afectado
        // Primero movemos el elemento temporalmente al final para sacarlo del medio
        // $re->orden_en_dia = 9999; 
        // $re->save();

        // En realidad, como SortableJS nos da el índice visual final, lo más fácil es:
        // 1. Obtener todos los ejercicios del bloque destino (excluyendo el que movemos) ordenados
        // 2. Insertar el nuestro en la posición correcta en la colección
        // 3. Recorrer y guardar orden

        $siblings = RutinaEjercicio::where('rutina_dia_id', $this->dia->id)
            ->where('rutina_bloque_id', $bloqueId)
            ->where('id', '!=', $ejercicioId)
            ->orderBy('orden_en_dia')
            ->get();

        // Insertar en la posición correcta (newOrden es 1-based)
        $siblings->splice($newOrden - 1, 0, [$re]);

        // Guardar nuevo orden
        foreach ($siblings as $index => $sibling) {
            $sibling->orden_en_dia = $index + 1;
            $sibling->rutina_bloque_id = $bloqueId; // Asegurar bloque
            $sibling->save();
        }

        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.admin.gestionar-dia-rutina', [
            'gruposMusculares' => \App\Models\GrupoMuscular::orderBy('nombre')->get(),
        ]);
    }
}
