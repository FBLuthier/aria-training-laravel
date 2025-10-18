<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =======================================================================
 * MODELO: TIPO USUARIO (ROL)
 * =======================================================================
 * 
 * Representa los diferentes roles/tipos de usuarios en el sistema.
 * Este modelo define los niveles de acceso y permisos.
 * 
 * TABLA: tipo_usuarios
 * 
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - rol: string(255) - Nombre del rol (ej: Administrador, Entrenador, Atleta)
 * 
 * ROLES DEL SISTEMA:
 * - ID 1: Administrador
 *   - Acceso completo al sistema
 *   - Gestión de usuarios, equipos, ejercicios
 *   - Configuración del sistema
 * 
 * - ID 2: Entrenador
 *   - Crear y gestionar rutinas
 *   - Asignar ejercicios a atletas
 *   - Ver progreso de atletas
 * 
 * - ID 3: Atleta
 *   - Ver rutinas asignadas
 *   - Registrar progreso
 *   - Visualizar estadísticas propias
 * 
 * RELACIONES:
 * - usuarios: HasMany - Usuarios que tienen este rol
 * 
 * CARACTERÍSTICAS:
 * - Sin timestamps (created_at, updated_at deshabilitados)
 * - Tabla de catálogo (generalmente no se modifica)
 * - Seeders definen los roles iniciales
 * 
 * USO:
 * ```php
 * // Obtener todos los administradores
 * $admins = TipoUsuario::find(1)->usuarios;
 * 
 * // Verificar si usuario es admin
 * if ($user->tipo_usuario_id === 1) {
 *     // Es administrador
 * }
 * 
 * // Eager loading para evitar N+1
 * $tipos = TipoUsuario::withRelations()->get();
 * ```
 * 
 * @property int $id
 * @property string $rol
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $usuarios
 * 
 * @package App\Models
 * @since 1.0
 */
class TipoUsuario extends Model
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
    protected $table = 'tipo_usuarios';
    
    /** 
     * @var bool Deshabilitar timestamps automáticos.
     * Esta tabla es un catálogo que rara vez cambia.
     */
    public $timestamps = false;
    
    /** @var array<string> Campos asignables en masa */
    protected $fillable = ['rol'];

    // =======================================================================
    //  RELACIONES
    // =======================================================================

    /**
     * Relación: Un tipo de usuario puede tener muchos usuarios.
     * 
     * Esta es una relación uno-a-muchos (1:N).
     * Un rol (ej: Entrenador) puede ser asignado a múltiples usuarios.
     * 
     * Uso:
     * ```php
     * // Obtener todos los usuarios con rol "Entrenador"
     * $entrenadores = TipoUsuario::find(2)->usuarios;
     * 
     * // Contar usuarios por rol
     * $totalAdmins = TipoUsuario::find(1)->usuarios()->count();
     * 
     * // Crear usuario con rol específico
     * $tipoAdmin = TipoUsuario::find(1);
     * $tipoAdmin->usuarios()->create([
     *     'usuario' => 'admin',
     *     'correo' => 'admin@example.com',
     *     // ...
     * ]);
     * ```
     * 
     * @return HasMany
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'tipo_usuario_id');
    }

    // =======================================================================
    //  QUERY SCOPES
    // =======================================================================

    /**
     * Scope para cargar todas las relaciones con eager loading.
     * 
     * Previene el problema N+1 al cargar usuarios junto con sus tipos.
     * 
     * Uso:
     * ```php
     * // Sin eager loading (N+1 queries)
     * $tipos = TipoUsuario::all();
     * foreach ($tipos as $tipo) {
     *     echo $tipo->usuarios; // Query por cada tipo
     * }
     * 
     * // Con eager loading (2 queries)
     * $tipos = TipoUsuario::withRelations()->get();
     * foreach ($tipos as $tipo) {
     *     echo $tipo->usuarios; // Ya cargados
     * }
     * ```
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with('usuarios');
    }
}