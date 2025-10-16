<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoBloqueEjercicio extends Model
{
    use HasFactory;

    protected $table = 'tipos_bloques_ejercicios';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    /**
     * Un Tipo de Bloque puede tener muchos Bloques de Ejercicios.
     */
    public function bloques(): HasMany
    {
        return $this->hasMany(BloqueEjercicioDia::class, 'id_tipo_bloque_ejercicio');
    }

    /**
     * Scope para cargar todas las relaciones con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with('bloques.rutinaDia');
    }
}