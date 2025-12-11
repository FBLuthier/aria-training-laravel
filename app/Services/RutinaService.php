<?php

namespace App\Services;

use App\Models\Rutina;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Servicio centralizado para lógica de negocio de rutinas.
 * 
 * Encapsula operaciones complejas relacionadas con rutinas de entrenamiento,
 * facilitando la reutilización y testeo del código.
 */
class RutinaService
{
    /**
     * Activar una rutina y desactivar las demás del mismo atleta.
     * 
     * Solo puede haber una rutina activa por atleta a la vez.
     * 
     * @param Rutina $rutina La rutina a activar/desactivar.
     * @return bool True si la rutina quedó activa, false si quedó inactiva.
     */
    public function toggleActive(Rutina $rutina): bool
    {
        if ($rutina->estado) {
            // Si ya está activa, desactivarla
            $rutina->update(['estado' => 0]);
            return false;
        }

        // Desactivar todas las otras rutinas de este atleta
        Rutina::where('atleta_id', $rutina->atleta_id)
            ->where('id', '!=', $rutina->id)
            ->update(['estado' => 0]);

        // Activar esta rutina
        $rutina->update(['estado' => 1]);
        return true;
    }

    /**
     * Obtener rutinas visibles para un usuario específico.
     * 
     * - Admin: Todas las rutinas
     * - Entrenador: Rutinas de sus atletas
     * - Atleta: Solo sus rutinas
     */
    public function getVisibleRutinas(
        User $viewer,
        ?int $athleteFilter = null,
        ?string $search = null,
        bool $includeTrashed = false
    ) {
        $query = Rutina::with(['atleta']);

        // Filtrar por rol del usuario
        if ($viewer->esEntrenador()) {
            $query->whereHas('atleta', function ($q) use ($viewer) {
                $q->where('entrenador_id', $viewer->id);
            });
        } elseif ($viewer->esAtleta()) {
            $query->where('atleta_id', $viewer->id);
        }

        // Filtro por atleta específico
        if ($athleteFilter) {
            $query->where('atleta_id', $athleteFilter);
        }

        // Búsqueda por nombre
        if ($search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        }

        // Papelera
        if ($includeTrashed) {
            $query->onlyTrashed();
        }

        return $query;
    }

    /**
     * Obtener la rutina activa de un atleta.
     */
    public function getActiveRutinaForAthlete(User $athlete): ?Rutina
    {
        return Rutina::where('atleta_id', $athlete->id)
            ->where('estado', 1)
            ->first();
    }

    /**
     * Obtener atletas disponibles para asignar rutinas según el rol del usuario.
     */
    public function getAvailableAthletes(User $viewer): Collection
    {
        if ($viewer->esEntrenador()) {
            return User::where('entrenador_id', $viewer->id)->get();
        }

        // Admin ve todos los atletas
        return User::where('tipo_usuario_id', 3)->get();
    }

    /**
     * Eliminar una rutina (soft delete).
     */
    public function delete(Rutina $rutina): bool
    {
        return $rutina->delete();
    }

    /**
     * Restaurar una rutina de la papelera.
     */
    public function restore(Rutina $rutina): bool
    {
        return $rutina->restore();
    }

    /**
     * Eliminar una rutina permanentemente.
     */
    public function forceDelete(Rutina $rutina): bool
    {
        return $rutina->forceDelete();
    }
}
