<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloqueEjercicioDia extends Model
{
    use HasFactory;

    protected $table = 'bloques_ejercicios_dias';

    protected $fillable = [
        'id_rutina_dia',
        'id_tipo_bloque_ejercicio',
        'orden',
        'descripcion',
    ];

    /**
     * Un Bloque pertenece a un Día de Rutina.
     */
    public function rutinaDia(): BelongsTo
    {
        return $this->belongsTo(RutinaDia::class, 'id_rutina_dia');
    }

    /**
     * Un Bloque pertenece a un Tipo de Bloque.
     */
    public function tipoBloque(): BelongsTo
    {
        return $this->belongsTo(TipoBloqueEjercicio::class, 'id_tipo_bloque_ejercicio');
    }

    /**
     * Un Bloque tiene muchos Ejercicios de Rutina.
     */
    public function rutinaEjercicios(): HasMany
    {
        return $this->hasMany(RutinaEjercicio::class, 'bloque_id');
    }

    /**
     * Scope para cargar todas las relaciones con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'rutinaDia.rutina',
            'tipoBloque',
            'rutinaEjercicios.ejercicio.equipo',
            'rutinaEjercicios.ejercicio.gruposMusculares'
        ]);
    }
}