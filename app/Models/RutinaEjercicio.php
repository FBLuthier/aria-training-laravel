<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =======================================================================
 * MODELO: RUTINA EJERCICIO
 * =======================================================================
 *
 * Representa la asignación de un ejercicio específico a un día de rutina.
 * Define cómo se ejecuta el ejercicio (series, repeticiones, orden, etc.).
 *
 * TABLA: rutina_ejercicios
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - rutina_dia_id: int (FK) - Día de la rutina
 * - ejercicio_id: int (FK) - Ejercicio del catálogo
 * - bloque_id: int|null (FK) - Bloque al que pertenece (opcional)
 * - series: int - Número de series a realizar
 * - repeticiones: string - Rango de repeticiones (ej: "8-12", "10", "AMRAP")
 * - indicaciones: text|null - Notas especiales
 * - orden_en_dia: int - Orden del ejercicio en el día
 * - orden_en_bloque: int|null - Orden dentro del bloque
 * - created_at, updated_at: timestamps
 *
 * EJEMPLO:
 * Día 1 - Pecho
 *   1. Press de Banca: 4 series x 8-12 reps (orden_en_dia: 1)
 *   2. Press Inclinado: 3 series x 10-15 reps (orden_en_dia: 2)
 *   3. SUPERSERIE (bloque_id: 1):
 *      - Aperturas: 3 series x 12-15 reps (orden_en_bloque: 1)
 *      - Cruces: 3 series x 12-15 reps (orden_en_bloque: 2)
 *
 * RELACIONES:
 * - rutinaDia: BelongsTo - Día al que pertenece
 * - ejercicio: BelongsTo - Ejercicio del catálogo
 * - bloque: BelongsTo - Bloque (superserie, circuito, etc.)
 * - registros: HasMany - Registros de series ejecutadas
 *
 * @property int $id
 * @property int $rutina_dia_id
 * @property int $ejercicio_id
 * @property int|null $bloque_id
 * @property int $series
 * @property string $repeticiones
 * @property string|null $indicaciones
 * @property int $orden_en_dia
 * @property int|null $orden_en_bloque
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read RutinaDia $rutinaDia
 * @property-read Ejercicio $ejercicio
 * @property-read BloqueEjercicioDia|null $bloque
 * @property-read \Illuminate\Database\Eloquent\Collection|RegistroSerie[] $registros
 *
 * @since 1.0
 */
class RutinaEjercicio extends Model
{
    use HasFactory;

    protected $table = 'rutina_ejercicios';

    protected $fillable = [
        'rutina_dia_id',
        'rutina_bloque_id',
        'ejercicio_id',
        'series',
        'repeticiones',
        'peso_sugerido',
        'indicaciones',
        'orden_en_dia',
        'orden_en_bloque',
        'tempo',
        'unidad_peso',
        'track_rpe',
        'track_rir',
        'unidad_repeticiones',
        'is_unilateral',
    ];

    protected $casts = [
        'tempo' => 'array',
    ];

    /**
     * Relación: Un ejercicio de rutina pertenece a un día.
     */
    public function rutinaDia(): BelongsTo
    {
        return $this->belongsTo(RutinaDia::class, 'rutina_dia_id');
    }

    /**
     * Relación: Un ejercicio de rutina referencia a un ejercicio del catálogo.
     *
     * El ejercicio define:
     * - Nombre
     * - Equipo necesario
     * - Grupos musculares trabajados
     *
     * @return BelongsTo
     */
    public function ejercicio()
    {
        return $this->belongsTo(Ejercicio::class)->withTrashed();
    }

    /**
     * Relación: Un ejercicio puede pertenecer a un bloque (opcional).
     *
     * Si rutina_bloque_id es NULL, es un ejercicio individual o "General".
     *
     * @return BelongsTo
     */
    public function bloque()
    {
        return $this->belongsTo(RutinaBloque::class, 'rutina_bloque_id');
    }

    /**
     * Relación: Un ejercicio tiene registros de series ejecutadas.
     *
     * Los atletas registran cada serie realizada:
     * - Peso usado
     * - Repeticiones completadas
     * - RIR (Reps in Reserve)
     * - Fecha de ejecución
     */
    public function registros(): HasMany
    {
        return $this->hasMany(RegistroSerie::class, 'rutina_ejercicio_id');
    }

    /**
     * Scope para cargar ejercicio completo con todas sus relaciones.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'rutinaDia.rutina',
            'ejercicio.equipo',
            'ejercicio.gruposMusculares',
            'bloque.tipoBloque',
            'registros.unidadMedida',
        ]);
    }
}
