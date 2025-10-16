<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidades_medida';
    public $timestamps = false;
    protected $fillable = ['nombre_unidad', 'simbolo'];

    /**
     * Una Unidad de Medida puede tener muchos Registros de Series.
     */
    public function registros(): HasMany
    {
        return $this->hasMany(RegistroSerie::class, 'unidad_medida_id');
    }

    /**
     * Scope para cargar todas las relaciones con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with('registros.rutinaEjercicio');
    }
}