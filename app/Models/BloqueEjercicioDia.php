<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =======================================================================
 * MODELO: BLOQUE EJERCICIO DÍA
 * =======================================================================
 *
 * Representa un bloque de ejercicios dentro de un día de rutina.
 * Permite agrupar ejercicios con técnicas avanzadas de entrenamiento.
 *
 * TABLA: bloques_ejercicios_dias
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - id_rutina_dia: int (FK) - Día de la rutina
 * - id_tipo_bloque_ejercicio: int (FK) - Tipo de bloque
 * - orden: int - Orden del bloque en el día
 * - descripcion: text|null - Notas o instrucciones
 * - created_at, updated_at: timestamps
 *
 * TIPOS DE BLOQUES:
 * - **Superserie**: Dos ejercicios consecutivos sin descanso
 *   Ejemplo: Press Banca + Fondos
 *
 * - **Biserie**: Dos ejercicios del mismo músculo sin descanso
 *   Ejemplo: Curl Barra + Curl Martillo (ambos bíceps)
 *
 * - **Triserie**: Tres ejercicios sin descanso
 *   Ejemplo: Press Hombro + Elevaciones Laterales + Elevaciones Frontales
 *
 * - **Circuito**: 4+ ejercicios con mínimo descanso
 *   Ejemplo: Burpees + Sentadillas + Flexiones + Abdominales
 *
 * - **Drop Set**: Reducción progresiva de peso
 *   Ejemplo: Press Banca 100kg → 80kg → 60kg (sin descanso)
 *
 * ESTRUCTURA:
 * Día 1 - Pecho
 *   Ejercicio individual: Press de Banca (4x8-12)
 *   BLOQUE (Superserie):
 *     ├── Aperturas con Mancuernas (3x12-15)
 *     └── Cruces en Polea (3x12-15)
 *   Ejercicio individual: Fondos (3xAMRAP)
 *
 * RELACIONES:
 * - rutinaDia: BelongsTo - Día al que pertenece
 * - tipoBloque: BelongsTo - Tipo de bloque (Superserie, etc.)
 * - rutinaEjercicios: HasMany - Ejercicios del bloque
 *
 * @property int $id
 * @property int $id_rutina_dia
 * @property int $id_tipo_bloque_ejercicio
 * @property int $orden
 * @property string|null $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read RutinaDia $rutinaDia
 * @property-read TipoBloqueEjercicio $tipoBloque
 * @property-read \Illuminate\Database\Eloquent\Collection|RutinaEjercicio[] $rutinaEjercicios
 *
 * @since 1.0
 */
class BloqueEjercicioDia extends Model
{
    use HasFactory;

    protected $table = 'bloques_ejercicios_dias';

    protected $fillable = [
        'id_rutina_dia',
        'id_tipo_bloque_ejercicio',
        'orden',
        'descripcion',
    ];

    /**
     * Relación: Un bloque pertenece a un día de rutina.
     */
    public function rutinaDia(): BelongsTo
    {
        return $this->belongsTo(RutinaDia::class, 'id_rutina_dia');
    }

    /**
     * Relación: Un bloque tiene un tipo (Superserie, Circuito, etc.).
     */
    public function tipoBloque(): BelongsTo
    {
        return $this->belongsTo(TipoBloqueEjercicio::class, 'id_tipo_bloque_ejercicio');
    }

    /**
     * Relación: Un bloque contiene múltiples ejercicios.
     *
     * Los ejercicios dentro del bloque se ejecutan según el tipo:
     * - Superserie: uno tras otro sin descanso
     * - Circuito: en rotación continua
     * - Drop set: con reducción de peso
     */
    public function rutinaEjercicios(): HasMany
    {
        return $this->hasMany(RutinaEjercicio::class, 'bloque_id');
    }

    /**
     * Scope para cargar bloque completo con ejercicios y detalles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'rutinaDia.rutina',
            'tipoBloque',
            'rutinaEjercicios.ejercicio.equipo',
            'rutinaEjercicios.ejercicio.gruposMusculares',
        ]);
    }
}
