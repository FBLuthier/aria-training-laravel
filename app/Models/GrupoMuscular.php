<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // 1. Añadimos la importación

class GrupoMuscular extends Model
{
    use HasFactory;

    protected $table = 'grupos_musculares';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    /**
     * Define la relación inversa muchos a muchos: un Grupo Muscular pertenece a muchos Ejercicios.
     */
    public function ejercicios(): BelongsToMany // 2. La función ahora está DENTRO de la clase
    {
        return $this->belongsToMany(Ejercicio::class, 'ejercicios_x_grupo_muscular', 'grupo_muscular_id', 'ejercicio_id');
    }
}