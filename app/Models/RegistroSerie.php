<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * =======================================================================
 * MODELO: REGISTRO SERIE
 * =======================================================================
 *
 * Representa el registro de una serie ejecutada por un atleta.
 * Permite trackear el progreso y rendimiento en cada ejercicio.
 *
 * TABLA: registro_series
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - rutina_ejercicio_id: int (FK) - Ejercicio de la rutina
 * - serie_numero: int - Número de la serie (1, 2, 3, etc.)
 * - lado: string|null - 'left', 'right' para unilaterales, null para normales
 * - peso: decimal|null - Peso usado
 * - reps: int|null - Repeticiones realizadas
 * - rpe: decimal|null - Rate of Perceived Exertion
 * - rir: int|null - Reps in Reserve
 * - notas: text|null - Observaciones del atleta
 * - completed_at: timestamp|null - Cuando se completó la serie
 * - created_at, updated_at: timestamps
 *
 * @property int $id
 * @property int $rutina_ejercicio_id
 * @property int $serie_numero
 * @property string|null $lado
 * @property float|null $peso
 * @property int|null $reps
 * @property float|null $rpe
 * @property int|null $rir
 * @property string|null $notas
 * @property \Carbon\Carbon|null $completed_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read RutinaEjercicio $rutinaEjercicio
 *
 * @since 1.0
 */
class RegistroSerie extends Model
{
    use HasFactory;

    protected $table = 'registro_series';

    protected $fillable = [
        'rutina_ejercicio_id',
        'serie_numero',
        'lado',
        'peso',
        'reps',
        'rpe',
        'rir',
        'observaciones',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'peso' => 'decimal:2',
        'rpe' => 'decimal:1',
    ];

    /**
     * Relación: Un registro pertenece a un ejercicio de rutina.
     */
    public function rutinaEjercicio(): BelongsTo
    {
        return $this->belongsTo(RutinaEjercicio::class, 'rutina_ejercicio_id');
    }

    /**
     * Scope para cargar registro con contexto completo.
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'rutinaEjercicio.ejercicio.equipo',
            'rutinaEjercicio.rutinaDia.rutina',
        ]);
    }
}

