<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RutinaDia extends Model
{
    use HasFactory;

    protected $table = 'rutina_dias';

    protected $fillable = [
        'rutina_id',
        'numero_dia',
        'nombre_dia',
    ];

    /**
     * Un Día de Rutina pertenece a una Rutina.
     */
    public function rutina(): BelongsTo
    {
        return $this->belongsTo(Rutina::class, 'rutina_id');
    }

    /**
     * Un Día de Rutina tiene muchos Bloques de Ejercicios.
     */
    public function bloques(): HasMany
    {
        return $this->hasMany(BloqueEjercicioDia::class, 'rutina_dia_id');
    }

    /**
     * Un Día de Rutina tiene muchos Ejercicios de Rutina.
     */
    public function rutinaEjercicios(): HasMany
    {
        return $this->hasMany(RutinaEjercicio::class, 'rutina_dia_id');
    }

    /**
     * Scope para cargar todas las relaciones con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'rutina.usuario:id,nombre_1,apellido_1',
            'bloques.tipoBloque',
            'rutinaEjercicios.ejercicio.equipo',
            'rutinaEjercicios.ejercicio.gruposMusculares'
        ]);
    }
}