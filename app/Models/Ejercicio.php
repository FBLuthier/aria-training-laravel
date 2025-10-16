<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ejercicio extends Model
{
    use HasFactory;

    protected $table = 'ejercicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'equipo_id',
        'estado',
    ];

    /**
     * Define la relación: un Ejercicio pertenece a un Equipo.
     */
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    /**
     * Define la relación muchos a muchos: un Ejercicio puede tener muchos Grupos Musculares.
     */
    public function gruposMusculares(): BelongsToMany
    {
        return $this->belongsToMany(GrupoMuscular::class, 'ejercicios_x_grupo_muscular', 'ejercicio_id', 'grupo_muscular_id');
    }

    /**
     * Scope para cargar todas las relaciones con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with(['equipo:id,nombre', 'gruposMusculares:id,nombre']);
    }
}