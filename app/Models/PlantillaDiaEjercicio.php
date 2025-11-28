<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantillaDiaEjercicio extends Model
{
    use HasFactory;

    protected $table = 'plantilla_dia_ejercicios';

    protected $fillable = [
        'plantilla_dia_id',
        'ejercicio_id',
        'series',
        'repeticiones',
        'peso_sugerido',
        'descanso_segundos',
        'indicaciones',
        'orden',
        'tempo',
        'unidad_peso',
    ];

    protected $casts = [
        'tempo' => 'array',
    ];

    /**
     * La plantilla a la que pertenece este detalle.
     */
    public function plantillaDia(): BelongsTo
    {
        return $this->belongsTo(PlantillaDia::class, 'plantilla_dia_id');
    }

    /**
     * El ejercicio referenciado.
     */
    public function ejercicio(): BelongsTo
    {
        return $this->belongsTo(Ejercicio::class, 'ejercicio_id');
    }
}
