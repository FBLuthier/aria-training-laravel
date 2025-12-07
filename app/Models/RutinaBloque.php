<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RutinaBloque extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rutina_bloques';

    protected $fillable = [
        'rutina_dia_id',
        'nombre',
        'orden',
    ];

    public function rutinaDia()
    {
        return $this->belongsTo(RutinaDia::class);
    }

    public function rutinaEjercicios()
    {
        return $this->hasMany(RutinaEjercicio::class)->orderBy('orden_en_dia');
    }
}
