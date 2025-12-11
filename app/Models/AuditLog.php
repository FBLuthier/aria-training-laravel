<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * =======================================================================
 * MODELO: AUDIT LOG (REGISTRO DE AUDITORÍA)
 * =======================================================================
 *
 * Registra TODAS las operaciones críticas del sistema para trazabilidad,
 * seguridad y cumplimiento de normativas. Cada cambio queda documentado.
 *
 * TABLA: audit_logs
 *
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - user_id: int (FK) - Usuario que realizó la acción
 * - action: string - Tipo de acción (create, update, delete, restore, force_delete)
 * - model_type: string - Clase del modelo afectado (App\Models\Equipo)
 * - model_id: int - ID del registro afectado
 * - old_values: json|null - Valores ANTES del cambio (para update/delete)
 * - new_values: json|null - Valores DESPUÉS del cambio (para create/update)
 * - ip_address: string - IP desde donde se hizo la acción
 * - user_agent: string - Navegador/cliente usado
 * - created_at, updated_at: timestamps
 *
 * ACCIONES REGISTRADAS:
 * - create: Creación de nuevo registro
 * - update: Modificación de registro existente
 * - delete: Eliminación suave (soft delete)
 * - restore: Restauración desde papelera
 * - force_delete: Eliminación permanente (⚠️ irreversible)
 *
 * USOS:
 * - **Seguridad**: Detectar acciones maliciosas
 * - **Auditoría**: Cumplir normativas (SOX, GDPR, etc.)
 * - **Debugging**: Rastrear cambios inesperados
 * - **Recuperación**: Restaurar datos borrados accidentalmente
 * - **Análisis**: Entender patrones de uso
 *
 * EJEMPLO DE REGISTRO:
 * ```
 * user_id: 1 (Admin)
 * action: update
 * model_type: App\Models\Equipo
 * model_id: 5
 * old_values: {"nombre": "Mancuernas"}
 * new_values: {"nombre": "Mancuernas 10kg"}
 * ip_address: 192.168.1.100
 * user_agent: Mozilla/5.0...
 * created_at: 2025-10-17 01:30:00
 * ```
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $model_type
 * @property int $model_id
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string $ip_address
 * @property string $user_agent
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $user
 *
 * @since 1.0
 */
class AuditLog extends Model
{
    // =======================================================================
    //  TRAITS
    // =======================================================================

    /** @var HasFactory Permite usar factories para testing */
    use HasFactory;

    // =======================================================================
    //  CONFIGURACIÓN DEL MODELO
    // =======================================================================

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'audit_logs';

    /** @var array<string> Campos asignables en masa */
    protected $fillable = [
        'user_id',      // ID del usuario que hizo la acción
        'action',       // create, update, delete, restore, force_delete
        'model_type',   // Clase completa del modelo (App\Models\Equipo)
        'model_id',     // ID del registro afectado
        'old_values',   // Valores antes del cambio (JSON)
        'new_values',   // Valores después del cambio (JSON)
        'ip_address',   // IP del usuario
        'user_agent',   // Navegador/cliente
    ];

    /**
     * Casts de atributos a tipos nativos.
     *
     * Los valores JSON se convierten automáticamente a arrays de PHP.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',    // JSON → array
        'new_values' => 'array',    // JSON → array
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =======================================================================
    //  RELACIONES
    // =======================================================================

    /**
     * Relación: Usuario que realizó la acción auditada.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación polimórfica al modelo auditado.
     *
     * Permite acceder al registro original que fue modificado.
     * IMPORTANTE: Puede retornar null si el registro fue eliminado permanentemente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function auditable()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    // =======================================================================
    //  QUERY SCOPES
    // =======================================================================

    /**
     * Filtra logs por tipo de acción.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $action  create, update, delete, restore, force_delete
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Filtra logs por tipo de modelo.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $modelType  Clase completa (App\Models\Equipo)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModelType($query, string $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Filtra logs por usuario específico.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId  ID del usuario
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filtra logs en un rango de fechas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $startDate  Fecha inicial
     * @param  string  $endDate  Fecha final
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Carga relaciones con eager loading.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with('user:id,nombre_1,apellido_1,correo');
    }

    // =======================================================================
    //  MÉTODOS HELPER ESTÁTICOS
    // =======================================================================

    /**
     * Método base para crear un registro de auditoría.
     *
     * Captura automáticamente usuario, IP y user agent del request actual.
     *
     * @param  string  $action  Tipo de acción
     * @param  Model  $model  Modelo afectado
     * @param  array|null  $oldValues  Valores anteriores
     * @param  array|null  $newValues  Valores nuevos
     */
    public static function log(string $action, Model $model, ?array $oldValues = null, ?array $newValues = null): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Registra creación de un modelo.
     */
    public static function logCreate(Model $model): self
    {
        return static::log('create', $model, null, $model->toArray());
    }

    /**
     * Registra actualización de un modelo.
     *
     * @param  array  $oldValues  Valores antes del cambio
     */
    public static function logUpdate(Model $model, array $oldValues): self
    {
        return static::log('update', $model, $oldValues, $model->toArray());
    }

    /**
     * Registra eliminación suave de un modelo.
     */
    public static function logDelete(Model $model): self
    {
        return static::log('delete', $model, $model->toArray(), null);
    }

    /**
     * Registra restauración de un modelo.
     */
    public static function logRestore(Model $model): self
    {
        return static::log('restore', $model, null, $model->toArray());
    }

    /**
     * Registra eliminación permanente de un modelo.
     */
    public static function logForceDelete(Model $model): self
    {
        return static::log('force_delete', $model, $model->toArray(), null);
    }
}
