<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroSerie extends Model
{
    use HasFactory;

    protected $table = 'registro_series';

    protected $fillable = [
        'id_rutina_ejercicio',
        'id_unidad_medida',
        'numero_serie',
        'valor_registrado',
        'repeticiones_realizadas',
        'observaciones',
    ];

    /**
     * Un Registro de Serie pertenece a un Ejercicio de Rutina.
     */
    public function rutinaEjercicio(): BelongsTo
    {
        return $this->belongsTo(RutinaEjercicio::class, 'id_rutina_ejercicio');
    }

    /**
     * Un Registro de Serie tiene una Unidad de Medida.
     */
    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida');
    }
}