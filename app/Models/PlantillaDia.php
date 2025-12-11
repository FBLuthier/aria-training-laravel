<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlantillaDia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'plantillas_dias';

    protected $fillable = [
        'nombre',
        'usuario_id',
        'entrenador_id',
        'descripcion',
    ];

    /**
     * El atleta dueño de la plantilla.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * El entrenador que creó la plantilla.
     */
    public function entrenador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entrenador_id');
    }

    /**
     * Los ejercicios que componen esta plantilla.
     */
    public function ejercicios(): HasMany
    {
        return $this->hasMany(PlantillaDiaEjercicio::class, 'plantilla_dia_id')->orderBy('orden');
    }

    /**
     * Scope para filtrar plantillas visibles para el usuario actual.
     * - Si es Entrenador: Ve las plantillas de sus atletas.
     * - Si es Atleta: Ve sus propias plantillas.
     */
    public function scopeForUser($query, User $user)
    {
        if ($user->esEntrenador()) {
            return $query->where('entrenador_id', $user->id);
        }

        if ($user->esAtleta()) {
            return $query->where('usuario_id', $user->id);
        }

        return $query; // Admin ve todo o lógica por defecto
    }
}
