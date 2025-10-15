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
        'rutina_dia_id',
        'ejercicio_id',
        'bloque_id',
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
        return $this->belongsTo(RutinaDia::class, 'rutina_dia_id');
    }

    /**
     * Un Ejercicio de Rutina se asocia a un Ejercicio del catálogo.
     */
    public function ejercicio(): BelongsTo
    {
        return $this->belongsTo(Ejercicio::class, 'ejercicio_id');
    }

    /**
     * Un Ejercicio de Rutina PUEDE pertenecer a un Bloque.
     */
    public function bloque(): BelongsTo
    {
        return $this->belongsTo(BloqueEjercicioDia::class, 'bloque_id');
    }

    /**
     * Un Ejercicio de Rutina tiene muchos Registros de Series.
     */
    public function registros(): HasMany
    {
        return $this->hasMany(RegistroSerie::class, 'rutina_ejercicio_id');
    }
}