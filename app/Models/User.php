<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * =======================================================================
 * MODELO: USER (USUARIO)
 * =======================================================================
 * 
 * Representa un usuario del sistema de gestión de gimnasio.
 * Extiende de Authenticatable para integración con el sistema de autenticación.
 * 
 * TABLA: usuarios
 * 
 * COLUMNAS PRINCIPALES:
 * - id: int (PK, auto-increment)
 * - usuario: string(255) - Nombre de usuario único para login
 * - correo: string(255) - Email único
 * - contrasena: string(255) - Hash de la contraseña
 * - nombre_1, nombre_2: Primer y segundo nombre
 * - apellido_1, apellido_2: Primer y segundo apellido
 * - telefono: string(20) - Teléfono de contacto
 * - fecha_nacimiento: date - Fecha de nacimiento
 * - estado: enum - Estado del usuario (activo, inactivo, etc.)
 * - tipo_usuario_id: int (FK) - Tipo de usuario (Admin, Entrenador, Atleta)
 * 
 * TIPOS DE USUARIO:
 * 1. Administrador (tipo_usuario_id = 1): Acceso total al sistema
 * 2. Entrenador (tipo_usuario_id = 2): Gestión de rutinas y atletas
 * 3. Atleta (tipo_usuario_id = 3): Visualización de rutinas propias
 * 
 * RELACIONES:
 * - tipoUsuario: BelongsTo - Tipo de usuario (rol)
 * - rutinas: HasMany - Rutinas creadas (si es entrenador)
 * - auditLogs: HasMany - Registros de auditoría de acciones
 * 
 * CARACTERÍSTICAS:
 * - Autenticación Laravel integrada
 * - Contraseña hasheada automáticamente
 * - Factory para generar datos de prueba
 * - Notificaciones habilitadas
 * 
 * SEGURIDAD:
 * - Contraseña nunca se expone en JSON (hidden)
 * - Hash automático al guardar
 * - Remember token para "recordarme"
 * 
 * @property int $id
 * @property string $usuario
 * @property string $correo
 * @property string $contrasena
 * @property string $nombre_1
 * @property string|null $nombre_2
 * @property string $apellido_1
 * @property string|null $apellido_2
 * @property string|null $telefono
 * @property \Carbon\Carbon|null $fecha_nacimiento
 * @property string $estado
 * @property int $tipo_usuario_id
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read TipoUsuario $tipoUsuario
 * @property-read \Illuminate\Database\Eloquent\Collection|Rutina[] $rutinas
 * @property-read \Illuminate\Database\Eloquent\Collection|AuditLog[] $auditLogs
 * 
 * @package App\Models
 * @since 1.0
 */
class User extends Authenticatable
{
    // =======================================================================
    //  TRAITS
    // =======================================================================
    
    /** @var HasFactory Permite usar factories para testing */
    /** @var Notifiable Habilita envío de notificaciones al usuario */
    use HasFactory, Notifiable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    // =======================================================================
    //  SCOPES (FILTROS)
    // =======================================================================

    /**
     * Aplica los filtros de búsqueda y estado (papelera).
     */
    public function scopeApplyFilters($query, $search, $showingTrash)
    {
        $query->when($search, function ($q) use ($search) {
            $q->where('nombre_1', 'like', '%' . $search . '%')
              ->orWhere('apellido_1', 'like', '%' . $search . '%')
              ->orWhere('correo', 'like', '%' . $search . '%')
              ->orWhere('usuario', 'like', '%' . $search . '%');
        });

        if ($showingTrash) {
            $query->onlyTrashed();
        }

        return $query;
    }

    /**
     * Aplica filtros y ordenamiento (usado por BaseCrudComponent).
     */
    public function scopeFiltered($query, $search, $showingTrash, $sortField, $sortDirection)
    {
        return $query->applyFilters($search, $showingTrash)
            ->orderBy($sortField, $sortDirection);
    }

    // =======================================================================
    //  CONFIGURACIÓN DEL MODELO
    // =======================================================================

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'usuarios';

    /**
     * Campos asignables en masa (mass assignment).
     * 
     * Estos campos pueden ser asignados usando User::create() o $user->fill()
     * sin riesgo de asignación masiva maliciosa.
     *
     * @var array<string>
     */
    protected $fillable = [
        'usuario',              // Username para login
        'correo',               // Email único
        'contrasena',           // Password (se hashea automáticamente)
        'nombre_1',             // Primer nombre
        'nombre_2',             // Segundo nombre (opcional)
        'apellido_1',           // Primer apellido
        'apellido_2',           // Segundo apellido (opcional)
        'telefono',             // Teléfono de contacto
        'fecha_nacimiento',     // Fecha de nacimiento
        'estado',               // Estado del usuario
        'tipo_usuario_id',      // Tipo de usuario (FK a tipos_usuarios)
        'entrenador_id',        // ID del entrenador asignado (para atletas)
    ];

    /**
     * Atributos que deben ocultarse en arrays/JSON.
     * 
     * Estos campos NO se incluyen cuando el modelo se serializa
     * (ej: return response()->json($user)).
     * 
     * SEGURIDAD: La contraseña NUNCA debe exponerse en APIs.
     *
     * @var array<string>
     */
    protected $hidden = [
        'contrasena',      // Contraseña hasheada
        'remember_token',  // Token para "recordarme"
    ];

    // =======================================================================
    //  CASTS Y MUTADORES
    // =======================================================================

    /**
     * Atributos que deben castearse a tipos nativos.
     * 
     * 'hashed' hace que Laravel automáticamente:
     * - Hashee la contraseña al asignar: $user->contrasena = 'plain'
     * - Mantenga el hash al recuperar de BD
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'contrasena' => 'hashed',  // Hash automático de contraseña
            'fecha_nacimiento' => 'date',
        ];
    }

    // =======================================================================
    //  MÉTODOS DE AUTENTICACIÓN
    // =======================================================================

    /**
     * Obtiene la contraseña para autenticación.
     * 
     * Laravel espera que el campo se llame 'password', pero en esta
     * aplicación se usa 'contrasena'. Este método mapea el campo correcto.
     * 
     * @return string Hash de la contraseña
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // =======================================================================
    //  RELACIONES
    // =======================================================================

    /**
     * Relación: Un usuario pertenece a un tipo de usuario (rol).
     * 
     * Esta es una relación muchos-a-uno (N:1).
     * Múltiples usuarios pueden tener el mismo tipo (ej: varios Entrenadores).
     * 
     * TIPOS DE USUARIO:
     * - 1: Administrador (acceso total)
     * - 2: Entrenador (crea rutinas)
     * - 3: Atleta (sigue rutinas)
     * 
     * Uso:
     * ```php
     * $user->tipoUsuario->rol; // "Administrador"
     * if ($user->tipo_usuario_id === 1) {
     *     // Es administrador
     * }
     * ```
     * 
     * @return BelongsTo
     */
    public function tipoUsuario(): BelongsTo
    {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario_id');
    }

    /**
     * Relación: Un usuario puede tener muchas rutinas.
     * 
     * Esta relación aplica principalmente a ENTRENADORES.
     * Los entrenadores crean rutinas para los atletas.
     * 
     * Uso:
     * ```php
     * // Obtener rutinas creadas por un entrenador
     * $rutinas = $entrenador->rutinas;
     * 
     * // Contar rutinas
     * $cantidad = $entrenador->rutinas()->count();
     * 
     * // Crear nueva rutina
     * $entrenador->rutinas()->create([...]);
     * ```
     * 
     * @return HasMany
     */
    public function rutinas(): HasMany
    {
        return $this->hasMany(Rutina::class, 'usuario_id');
    }

    /**
     * Relación: Un usuario puede tener muchos registros de auditoría.
     * 
     * Registra todas las acciones realizadas por el usuario:
     * - Creaciones
     * - Actualizaciones
     * - Eliminaciones
     * 
     * Útil para:
     * - Rastreo de cambios
     * - Auditorías de seguridad
     * - Recuperación de datos
     * 
     * Uso:
     * ```php
     * // Ver últimas acciones del usuario
     * $acciones = $user->auditLogs()->latest()->take(10)->get();
     * 
     * // Buscar cambios en modelo específico
     * $cambios = $user->auditLogs()
     *     ->where('model_type', 'Equipo')
     *     ->get();
     * ```
     * 
     * @return HasMany
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Relación: Un Atleta pertenece a un Entrenador.
     * 
     * @return BelongsTo
     */
    public function entrenador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entrenador_id');
    }

    /**
     * Relación: Un Entrenador tiene muchos Atletas.
     * 
     * @return HasMany
     */
    public function atletas(): HasMany
    {
        return $this->hasMany(User::class, 'entrenador_id');
    }
}
