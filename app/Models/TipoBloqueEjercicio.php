<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =======================================================================
 * MODELO: TIPO BLOQUE EJERCICIO (CATÁLOGO)
 * =======================================================================
 *
 * Representa los diferentes tipos de bloques de ejercicios que pueden
 * organizarse dentro de una rutina. Define cómo se agrupan los ejercicios.
 *
 * TABLA: tipos_bloques_ejercicios
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - nombre: string(255) - Nombre del tipo de bloque
 *
 * TIPOS DE BLOQUES TÍPICOS:
 * - Superserie: Dos ejercicios consecutivos sin descanso
 * - Biserie: Dos ejercicios del mismo músculo sin descanso
 * - Triserie: Tres ejercicios consecutivos sin descanso
 * - Serie Gigante: Cuatro o más ejercicios sin descanso
 * - Serie Compuesta: Dos ejercicios para el mismo músculo
 * - Circuito: Secuencia de ejercicios con mínimo descanso
 * - Pirámide: Series con carga creciente/decreciente
 * - Drop Set: Serie con reducción progresiva de peso
 * - Rest-Pause: Series con pausas cortas
 *
 * USO EN RUTINAS:
 * Permite organizar ejercicios en bloques especiales para:
 * - Aumentar intensidad
 * - Optimizar tiempo
 * - Crear estímulos específicos
 * - Variar metodología de entrenamiento
 *
 * RELACIONES:
 * - bloques: HasMany - Bloques que usan este tipo
 *
 * CARACTERÍSTICAS:
 * - Sin timestamps (tabla de catálogo)
 * - Seeders definen tipos iniciales
 *
 * @property int $id
 * @property string $nombre
 * @property-read \Illuminate\Database\Eloquent\Collection|BloqueEjercicioDia[] $bloques
 *
 * @since 1.0
 */
class TipoBloqueEjercicio extends Model
{
    use HasFactory;

    protected $table = 'tipos_bloques_ejercicios';

    public $timestamps = false;

    protected $fillable = ['nombre'];

    /**
     * Relación: Un tipo puede usarse en muchos bloques.
     */
    public function bloques(): HasMany
    {
        return $this->hasMany(BloqueEjercicioDia::class, 'id_tipo_bloque_ejercicio');
    }

    /**
     * Scope para cargar bloques con sus días (eager loading).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with('bloques.rutinaDia');
    }
}
