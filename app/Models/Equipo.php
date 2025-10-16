<?php

namespace App\Models;

use App\Models\Builders\EquipoQueryBuilder;
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
     * Crea una nueva instancia del query builder personalizado.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return EquipoQueryBuilder
     */
    public function newEloquentBuilder($query): EquipoQueryBuilder
    {
        return new EquipoQueryBuilder($query);
    }

    /**
     * Define la relación inversa: un Equipo tiene muchos Ejercicios.
     */
    public function ejercicios(): HasMany
    {
        return $this->hasMany(Ejercicio::class, 'equipo_id');
    }
}