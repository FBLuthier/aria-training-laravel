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
 * - unidad_medida_id: int (FK) - Unidad de medida (kg, lb, etc.)
 * - numero_serie: int - Número de la serie (1, 2, 3, etc.)
 * - valor_registrado: decimal - Valor (peso, tiempo, distancia)
 * - repeticiones_realizadas: int - Repeticiones completadas
 * - observaciones: text|null - Notas del atleta
 * - created_at, updated_at: timestamps (fecha de ejecución)
 *
 * EJEMPLOS DE REGISTROS:
 *
 * Press de Banca - Día 1:
 *   Serie 1: 100 kg x 12 reps (RIR: 2)
 *   Serie 2: 100 kg x 10 reps (RIR: 1)
 *   Serie 3: 100 kg x 8 reps (RIR: 0, fallo)
 *   Serie 4: 90 kg x 10 reps (drop set)
 *
 * Plancha Abdominal:
 *   Serie 1: 60 segundos
 *   Serie 2: 55 segundos
 *   Serie 3: 50 segundos
 *
 * Cardio - Cinta:
 *   Registro: 5 km en 25 minutos
 *
 * PROGRESO EN EL TIEMPO:
 * Al comparar registros entre sesiones, se puede ver:
 * - Incremento de peso levantado
 * - Mejora en repeticiones
 * - Aumento de volumen total
 * - Tendencias de rendimiento
 *
 * RELACIONES:
 * - rutinaEjercicio: BelongsTo - Ejercicio programado
 * - unidadMedida: BelongsTo - Unidad (kg, lb, s, m, etc.)
 *
 * @property int $id
 * @property int $rutina_ejercicio_id
 * @property int $unidad_medida_id
 * @property int $numero_serie
 * @property float $valor_registrado
 * @property int $repeticiones_realizadas
 * @property string|null $observaciones
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read RutinaEjercicio $rutinaEjercicio
 * @property-read UnidadMedida $unidadMedida
 *
 * @since 1.0
 */
class RegistroSerie extends Model
{
    use HasFactory;

    protected $table = 'registro_series';

    protected $fillable = [
        'rutina_ejercicio_id',
        'unidad_medida_id',
        'numero_serie',
        'valor_registrado',
        'repeticiones_realizadas',
        'observaciones',
    ];

    /**
     * Relación: Un registro pertenece a un ejercicio de rutina.
     *
     * Permite saber qué ejercicio se estaba ejecutando.
     */
    public function rutinaEjercicio(): BelongsTo
    {
        return $this->belongsTo(RutinaEjercicio::class, 'rutina_ejercicio_id');
    }

    /**
     * Relación: Un registro usa una unidad de medida.
     *
     * Define cómo interpretar el valor_registrado:
     * - kg: kilogramos de peso
     * - lb: libras de peso
     * - s: segundos de duración
     * - m: metros de distancia
     */
    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    /**
     * Scope para cargar registro con contexto completo.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'rutinaEjercicio.ejercicio.equipo',
            'rutinaEjercicio.rutinaDia.rutina',
            'unidadMedida',
        ]);
    }
}
