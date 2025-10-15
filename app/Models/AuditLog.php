<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que realizó la acción.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene el modelo relacionado con esta auditoría.
     * Nota: Este método es genérico y puede devolver diferentes tipos de modelos.
     */
    public function auditable()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    /**
     * Scope para filtrar por acción específica.
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para filtrar por tipo de modelo.
     */
    public function scopeModelType($query, string $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope para filtrar por usuario específico.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para obtener registros en un rango de fechas.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Método helper para crear un log de auditoría.
     */
    public static function log(string $action, Model $model, array $oldValues = null, array $newValues = null): self
    {
        $userId = auth()->id();
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();

        return static::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Método helper para crear un log de creación.
     */
    public static function logCreate(Model $model): self
    {
        return static::log('create', $model, null, $model->toArray());
    }

    /**
     * Método helper para crear un log de actualización.
     */
    public static function logUpdate(Model $model, array $oldValues): self
    {
        return static::log('update', $model, $oldValues, $model->toArray());
    }

    /**
     * Método helper para crear un log de eliminación.
     */
    public static function logDelete(Model $model): self
    {
        return static::log('delete', $model, $model->toArray(), null);
    }

    /**
     * Método helper para crear un log de restauración.
     */
    public static function logRestore(Model $model): self
    {
        return static::log('restore', $model, null, $model->toArray());
    }

    /**
     * Método helper para crear un log de eliminación permanente.
     */
    public static function logForceDelete(Model $model): self
    {
        return static::log('force_delete', $model, $model->toArray(), null);
    }
}
