<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * =======================================================================
 * MODELO: GRUPO MUSCULAR
 * =======================================================================
 *
 * Representa un grupo muscular del cuerpo humano que puede ser trabajado
 * mediante ejercicios. Se usa para clasificar y organizar ejercicios.
 *
 * TABLA: grupos_musculares
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - nombre: string(255) - Nombre del grupo muscular
 *
 * EJEMPLOS DE GRUPOS MUSCULARES:
 * - Pecho (pectorales)
 * - Espalda (dorsales, trapecios)
 * - Piernas (cuádriceps, isquiotibiales, glúteos)
 * - Hombros (deltoides)
 * - Brazos (bíceps, tríceps)
 * - Core (abdominales, lumbares)
 *
 * RELACIONES:
 * - ejercicios: BelongsToMany - Ejercicios que trabajan este músculo
 *
 * CARACTERÍSTICAS:
 * - Sin timestamps (tabla de catálogo)
 * - Relación N:M con Ejercicio
 * - Eager loading optimizado
 *
 * USO:
 * ```php
 * // Obtener ejercicios que trabajan pecho
 * $pecho = GrupoMuscular::where('nombre', 'Pecho')->first();
 * $ejercicios = $pecho->ejercicios;
 *
 * // Buscar ejercicios con sus equipos
 * $grupos = GrupoMuscular::withRelations()->get();
 * ```
 *
 * @property int $id
 * @property string $nombre
 * @property-read \Illuminate\Database\Eloquent\Collection|Ejercicio[] $ejercicios
 *
 * @since 1.0
 */
class GrupoMuscular extends Model
{
    // =======================================================================
    //  TRAITS
    // =======================================================================

    /** @var HasFactory Permite usar factories para testing */
    use HasFactory;

    // =======================================================================
    //  CONFIGURACIÓN DEL MODELO
    // =======================================================================

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'grupos_musculares';

    /**
     * @var bool Deshabilitar timestamps automáticos.
     *           Esta es una tabla de catálogo que rara vez cambia.
     */
    public $timestamps = true;

    /** @var array<string> Campos asignables en masa */
    protected $fillable = ['nombre'];

    // =======================================================================
    //  RELACIONES
    // =======================================================================

    /**
     * Relación: Un grupo muscular puede ser trabajado por muchos ejercicios.
     *
     * Esta es una relación muchos-a-muchos (N:M).
     * Un músculo puede ser trabajado por varios ejercicios, y un ejercicio
     * puede trabajar varios músculos.
     *
     * TABLA PIVOT: ejercicios_x_grupo_muscular
     *
     * Ejemplos:
     * - Pecho → Press Banca, Flexiones, Aperturas
     * - Bíceps → Curl con Barra, Curl con Mancuernas, Curl Martillo
     * - Cuádriceps → Sentadillas, Prensa de Pierna, Zancadas
     *
     * Uso:
     * ```php
     * // Obtener todos los ejercicios para un músculo
     * $ejerciciosPecho = $pecho->ejercicios;
     *
     * // Contar ejercicios
     * $cantidad = $pecho->ejercicios()->count();
     *
     * // Buscar ejercicios con equipo específico
     * $ejercicios = $pecho->ejercicios()->where('equipo_id', 1)->get();
     * ```
     */
    public function ejercicios(): BelongsToMany
    {
        return $this->belongsToMany(
            Ejercicio::class,
            'ejercicios_x_grupo_muscular',
            'grupo_muscular_id',
            'ejercicio_id'
        );
    }

    // =======================================================================
    //  QUERY SCOPES
    // =======================================================================

    /**
     * Scope para cargar ejercicios con sus equipos (eager loading).
     *
     * Carga los ejercicios asociados al grupo muscular y también
     * el equipo de cada ejercicio, evitando el problema N+1.
     *
     * Uso:
     * ```php
     * // Sin eager loading (muchas queries)
     * $grupos = GrupoMuscular::all();
     * foreach ($grupos as $grupo) {
     *     foreach ($grupo->ejercicios as $ejercicio) {
     *         echo $ejercicio->equipo->nombre; // Query por cada ejercicio
     *     }
     * }
     *
     * // Con eager loading (pocas queries)
     * $grupos = GrupoMuscular::withRelations()->get();
     * foreach ($grupos as $grupo) {
     *     foreach ($grupo->ejercicios as $ejercicio) {
     *         echo $ejercicio->equipo->nombre; // Ya cargado
     *     }
     * }
     * ```
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with('ejercicios.equipo');
    }
}
