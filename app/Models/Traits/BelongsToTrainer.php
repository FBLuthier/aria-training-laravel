<?php

namespace App\Models\Traits;

use App\Models\Scopes\BelongsToTrainerScope;

trait BelongsToTrainer
{
    /**
     * Boot the BelongsToTrainer trait for a model.
     */
    public static function bootBelongsToTrainer(): void
    {
        static::addGlobalScope(new BelongsToTrainerScope);
    }
}
