<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipos';
    public $timestamps = false;
    protected $fillable = ['nombre'];

/**
     * Define la relación inversa: un Equipo tiene muchos Ejercicios.
     */
    public function ejercicios(): HasMany
    {
        return $this->hasMany(Ejercicio::class, 'id_equipo');
    }
}