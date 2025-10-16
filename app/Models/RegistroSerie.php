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
        'rutina_ejercicio_id',
        'unidad_medida_id',
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
        return $this->belongsTo(RutinaEjercicio::class, 'rutina_ejercicio_id');
    }

    /**
     * Un Registro de Serie tiene una Unidad de Medida.
     */
    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    /**
     * Scope para cargar todas las relaciones con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'rutinaEjercicio.ejercicio.equipo',
            'rutinaEjercicio.rutinaDia.rutina',
            'unidadMedida'
        ]);
    }
}