<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BelongsToTrainerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check() && auth()->user()->esEntrenador()) {
            // Si el modelo es User, necesitamos lógica especial para que el entrenador se vea a sí mismo
            if ($model instanceof \App\Models\User) {
                $builder->where(function ($query) {
                    $query->where('entrenador_id', auth()->id())
                        ->orWhere('id', auth()->id());
                });
            } else {
                // Para otros modelos (Rutinas, etc), solo filtrar por entrenador_id
                $builder->where('entrenador_id', auth()->id());
            }
        }
    }
}
