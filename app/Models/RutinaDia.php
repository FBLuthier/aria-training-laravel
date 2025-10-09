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
        'id_rutina',
        'numero_dia',
        'nombre_dia',
    ];

    /**
     * Un Día de Rutina pertenece a una Rutina.
     */
    public function rutina(): BelongsTo
    {
        return $this->belongsTo(Rutina::class, 'id_rutina');
    }

    /**
     * Un Día de Rutina tiene muchos Bloques de Ejercicios.
     */
    public function bloques(): HasMany
    {
        return $this->hasMany(BloqueEjercicioDia::class, 'id_rutina_dia');
    }

    /**
     * Un Día de Rutina tiene muchos Ejercicios de Rutina.
     */
    public function rutinaEjercicios(): HasMany
    {
        return $this->hasMany(RutinaEjercicio::class, 'id_rutina_dia');
    }
}