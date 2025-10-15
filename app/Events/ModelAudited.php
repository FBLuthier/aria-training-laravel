<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelAudited
{
    use Dispatchable, SerializesModels;

    public string $action;
    public Model $model;
    public ?array $oldValues;
    public ?array $newValues;

    /**
     * Create a new event instance.
     */
    public function __construct(string $action, Model $model, ?array $oldValues = null, ?array $newValues = null)
    {
        $this->action = $action;
        $this->model = $model;
        $this->oldValues = $oldValues;
        $this->newValues = $newValues;
    }
}
