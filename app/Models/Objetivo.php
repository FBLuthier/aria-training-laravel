<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =======================================================================
 * MODELO: OBJETIVO (CATÁLOGO)
 * =======================================================================
 *
 * Representa los objetivos de entrenamiento que pueden asignarse a rutinas.
 * Tabla de catálogo que define las metas que los atletas buscan alcanzar.
 *
 * TABLA: objetivos
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - nombre: string(255) - Nombre del objetivo
 *
 * OBJETIVOS TÍPICOS:
 * - Hipertrofia (ganancia de masa muscular)
 * - Fuerza (incremento de fuerza máxima)
 * - Resistencia (acondicionamiento aeróbico)
 * - Pérdida de peso (déficit calórico + ejercicio)
 * - Acondicionamiento general (fitness general)
 * - Rehabilitación (recuperación post-lesión)
 * - Tonificación (definición muscular)
 *
 * RELACIONES:
 * - rutinas: HasMany - Rutinas asociadas a este objetivo
 *
 * CARACTERÍSTICAS:
 * - Sin timestamps (tabla de catálogo)
 * - Seeders definen objetivos iniciales
 * - Eager loading optimizado
 *
 * USO:
 * ```php
 * // Obtener objetivo
 * $hipertrofia = Objetivo::where('nombre', 'Hipertrofia')->first();
 *
 * // Ver rutinas del objetivo
 * $rutinas = $hipertrofia->rutinas;
 *
 * // Cargar con relaciones
 * $objetivos = Objetivo::withRelations()->get();
 * ```
 *
 * @property int $id
 * @property string $nombre
 * @property-read \Illuminate\Database\Eloquent\Collection|Rutina[] $rutinas
 *
 * @since 1.0
 */
class Objetivo extends Model
{
    use HasFactory;

    protected $table = 'objetivos';

    public $timestamps = false;

    protected $fillable = ['nombre'];

    /**
     * Relación: Un objetivo puede tener muchas rutinas asociadas.
     */
    public function rutinas(): HasMany
    {
        return $this->hasMany(Rutina::class, 'objetivo_id');
    }

    /**
     * Scope para cargar rutinas con sus usuarios (eager loading).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with('rutinas.usuario');
    }
}
