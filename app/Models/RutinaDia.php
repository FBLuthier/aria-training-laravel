<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =======================================================================
 * MODELO: RUTINA DÍA
 * =======================================================================
 *
 * Representa un día específico dentro de una rutina de entrenamiento.
 * Organiza los ejercicios por sesiones de entrenamiento.
 *
 * TABLA: rutina_dias
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - rutina_id: int (FK) - Rutina a la que pertenece
 * - numero_dia: int - Número del día (1, 2, 3, etc.)
 * - nombre_dia: string(255) - Nombre descriptivo del día
 * - created_at, updated_at: timestamps
 *
 * EJEMPLOS DE DÍAS:
 * - Día 1: "Lunes - Pecho y Tríceps"
 * - Día 2: "Miércoles - Espalda y Bíceps"
 * - Día 3: "Viernes - Piernas"
 * - Push Day: "Push - Empuje (Pecho, Hombros, Tríceps)"
 * - Pull Day: "Pull - Jalón (Espalda, Bíceps)"
 * - Leg Day: "Leg - Piernas (Cuádriceps, Glúteos, Isquios)"
 *
 * ESTRUCTURA:
 * Rutina "Hipertrofia 4 días"
 *   └── RutinaDia "Día 1 - Pecho/Tríceps"
 *       ├── RutinaEjercicio: Press de Banca (4x8-12)
 *       ├── RutinaEjercicio: Aperturas (3x10-15)
 *       └── BloqueEjercicioDia: Superserie Tríceps
 *           ├── Extensiones
 *           └── Fondos
 *
 * RELACIONES:
 * - rutina: BelongsTo - Rutina padre
 * - bloques: HasMany - Bloques de ejercicios especiales
 * - rutinaEjercicios: HasMany - Ejercicios individuales del día
 *
 * @property int $id
 * @property int $rutina_id
 * @property int $numero_dia
 * @property string $nombre_dia
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Rutina $rutina
 * @property-read \Illuminate\Database\Eloquent\Collection|BloqueEjercicioDia[] $bloques
 * @property-read \Illuminate\Database\Eloquent\Collection|RutinaEjercicio[] $rutinaEjercicios
 *
 * @since 1.0
 */
class RutinaDia extends Model
{
    use HasFactory;

    protected $table = 'rutina_dias';

    protected $fillable = [
        'rutina_id',
        'numero_dia',
        'nombre_dia',
        'plantilla_dia_id',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Relación: Un día pertenece a una rutina.
     */
    public function rutina(): BelongsTo
    {
        return $this->belongsTo(Rutina::class, 'rutina_id');
    }

    /**
     * Relación: Un día puede provenir de una plantilla.
     */
    public function plantillaDia(): BelongsTo
    {
        return $this->belongsTo(PlantillaDia::class, 'plantilla_dia_id');
    }

    /**
     * Relación: Un día puede tener bloques de ejercicios especiales.
     *
     * Los bloques agrupan ejercicios con técnicas avanzadas:
     * - Superseries
     * - Triseries
     * - Circuitos
     * - Drop sets
     *
     * @return HasMany
     */
    public function bloques()
    {
        return $this->hasMany(RutinaBloque::class)->orderBy('orden');
    }

    /**
     * Relación: Un día tiene múltiples ejercicios.
     *
     * Cada ejercicio incluye:
     * - Ejercicio específico
     * - Series
     * - Repeticiones
     * - Descanso
     * - Notas
     *
     * @return HasMany
     */
    public function rutinaEjercicios()
    {
        return $this->hasMany(RutinaEjercicio::class)->orderBy('orden_en_dia');
    }

    /**
     * Scope para cargar día completo con todos sus datos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'rutina.usuario:id,nombre_1,apellido_1',
            'bloques.tipoBloque',
            'rutinaEjercicios.ejercicio.equipo',
            'rutinaEjercicios.ejercicio.gruposMusculares',
        ]);
    }
}
