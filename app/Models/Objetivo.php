<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Objetivo extends Model
{
    use HasFactory;

    protected $table = 'objetivos';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    /**
     * Un Objetivo puede tener muchas Rutinas.
     */
    public function rutinas(): HasMany
    {
        return $this->hasMany(Rutina::class, 'objetivo_id');
    }

    /**
     * Scope para cargar todas las relaciones con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with('rutinas.usuario');
    }
}