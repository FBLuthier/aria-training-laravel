<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RutinaEjercicio extends Model
{
    use HasFactory;

    protected $table = 'rutina_ejercicios';

    protected $fillable = [
        'id_rutina_dia',
        'id_ejercicio',
        'id_bloque',
        'series',
        'repeticiones',
        'indicaciones',
        'orden_en_dia',
        'orden_en_bloque',
    ];

    /**
     * Un Ejercicio de Rutina pertenece a un Día de Rutina.
     */
    public function rutinaDia(): BelongsTo
    {
        return $this->belongsTo(RutinaDia::class, 'id_rutina_dia');
    }

    /**
     * Un Ejercicio de Rutina se asocia a un Ejercicio del catálogo.
     */
    public function ejercicio(): BelongsTo
    {
        return $this->belongsTo(Ejercicio::class, 'id_ejercicio');
    }

    /**
     * Un Ejercicio de Rutina PUEDE pertenecer a un Bloque.
     */
    public function bloque(): BelongsTo
    {
        return $this->belongsTo(BloqueEjercicioDia::class, 'id_bloque');
    }

    /**
     * Un Ejercicio de Rutina tiene muchos Registros de Series.
     */
    public function registros(): HasMany
    {
        return $this->hasMany(RegistroSerie::class, 'id_rutina_ejercicio');
    }
}