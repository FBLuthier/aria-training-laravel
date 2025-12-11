<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =======================================================================
 * MODELO: UNIDAD DE MEDIDA (CATÁLOGO)
 * =======================================================================
 *
 * Representa las unidades de medida para registrar el progreso en ejercicios.
 * Tabla de catálogo que define cómo se miden las cargas y volúmenes.
 *
 * TABLA: unidades_medida
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - nombre_unidad: string(255) - Nombre completo de la unidad
 * - simbolo: string(10) - Símbolo/abreviatura
 *
 * UNIDADES TÍPICAS:
 * - Kilogramos (kg) - Peso en sistema métrico
 * - Libras (lb) - Peso en sistema imperial
 * - Repeticiones (reps) - Conteo de repeticiones
 * - Segundos (s) - Tiempo de duración
 * - Minutos (min) - Tiempo de duración
 * - Metros (m) - Distancia
 * - Kilómetros (km) - Distancia
 *
 * USO:
 * Los registros de series usan estas unidades para documentar:
 * - Peso levantado: 100 kg
 * - Tiempo sostenido: 60 s
 * - Distancia recorrida: 5 km
 *
 * RELACIONES:
 * - registros: HasMany - Registros de series que usan esta unidad
 *
 * CARACTERÍSTICAS:
 * - Sin timestamps (tabla de catálogo)
 * - Seeders definen unidades iniciales
 * - Eager loading optimizado
 *
 * @property int $id
 * @property string $nombre_unidad
 * @property string $simbolo
 * @property-read \Illuminate\Database\Eloquent\Collection|RegistroSerie[] $registros
 *
 * @since 1.0
 */
class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidades_medida';

    public $timestamps = false;

    protected $fillable = ['nombre_unidad', 'simbolo'];

    /**
     * Relación: Una unidad puede usarse en muchos registros de series.
     */
    public function registros(): HasMany
    {
        return $this->hasMany(RegistroSerie::class, 'unidad_medida_id');
    }

    /**
     * Scope para cargar registros con sus ejercicios (eager loading).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with('registros.rutinaEjercicio');
    }
}
