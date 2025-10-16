<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use HasFactory;

    protected $table = 'tipo_usuarios';
    public $timestamps = false;
    protected $fillable = ['rol'];

    /**
     * Relación con usuarios.
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'tipo_usuario_id');
    }

    /**
     * Scope para cargar todas las relaciones con eager loading.
     */
    public function scopeWithRelations($query)
    {
        return $query->with('usuarios');
    }
}