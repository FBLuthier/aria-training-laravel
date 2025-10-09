<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoBloqueEjercicio extends Model
{
    use HasFactory;

    protected $table = 'tipos_bloques_ejercicios';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}