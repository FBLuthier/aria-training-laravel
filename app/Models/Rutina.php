<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rutina extends Model
{
    use HasFactory;

    protected $table = 'rutinas';

    protected $fillable = [
        'nombre',
        'objetivo_id',
        'usuario_id',
        'estado',
    ];

    /**
     * Una Rutina pertenece a un Usuario (el entrenador que la creó).
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Una Rutina pertenece a un Objetivo.
     */
    public function objetivo(): BelongsTo
    {
        return $this->belongsTo(Objetivo::class, 'objetivo_id');
    }

    /**
     * Una Rutina tiene muchos Días de Rutina.
     */
    public function dias(): HasMany
    {
        return $this->hasMany(RutinaDia::class, 'rutina_id');
    }

    /**
     * Scope para cargar todas las relaciones básicas con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with(['usuario:id,nombre_1,apellido_1', 'objetivo:id,nombre']);
    }

    /**
     * Scope para cargar rutina completa con todos sus días y ejercicios.
     */
    public function scopeWithFullDetails($query)
    {
        return $query->with([
            'usuario:id,nombre_1,apellido_1',
            'objetivo:id,nombre',
            'dias.rutinaEjercicios.ejercicio.equipo',
            'dias.rutinaEjercicios.ejercicio.gruposMusculares',
            'dias.bloques.tipoBloque'
        ]);
    }
}