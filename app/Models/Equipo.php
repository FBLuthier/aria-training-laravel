<?php

namespace App\Models;

use App\Models\Builders\EquipoQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * =======================================================================
 * MODELO: EQUIPO
 * =======================================================================
 * 
 * Representa un equipo de gimnasio (mancuernas, barras, máquinas, etc.)
 * usado en la realización de ejercicios.
 * 
 * TABLA: equipos
 * 
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - nombre: string(255) - Nombre del equipo (único)
 * - deleted_at: timestamp|null - Soft delete (papelera)
 * 
 * RELACIONES:
 * - ejercicios: HasMany - Un equipo puede usarse en múltiples ejercicios
 * 
 * CARACTERÍSTICAS:
 * - Soft Deletes: Los equipos eliminados van a papelera
 * - Factory: Tiene factory para generar datos de prueba
 * - Query Builder: Usa EquipoQueryBuilder personalizado
 * - Sin timestamps: created_at/updated_at están deshabilitados
 * 
 * USO:
 * ```php
 * // Crear equipo
 * $equipo = Equipo::create(['nombre' => 'Mancuernas 10kg']);
 * 
 * // Buscar con query builder personalizado
 * $equipos = Equipo::query()
 *     ->search('Mancuernas')
 *     ->withoutEjercicios()
 *     ->get();
 * 
 * // Relaciones
 * $ejercicios = $equipo->ejercicios;
 * ```
 * 
 * @property int $id
 * @property string $nombre
 * @property \Carbon\Carbon|null $deleted_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ejercicio[] $ejercicios
 * 
 * @package App\Models
 * @since 1.0
 */
class Equipo extends Model
{
    // =======================================================================
    //  TRAITS
    // =======================================================================
    
    /** @var HasFactory Permite usar factories para testing */
    /** @var SoftDeletes Habilita eliminación suave (papelera) */
    use HasFactory, SoftDeletes;

    // =======================================================================
    //  CONFIGURACIÓN DEL MODELO
    // =======================================================================

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'equipos';
    
    /** @var bool Deshabilitar timestamps automáticos (created_at, updated_at) */
    public $timestamps = false;
    
    /** @var array<string> Campos asignables en masa (mass assignment) */
    protected $fillable = ['nombre', 'usuario_id'];

    // =======================================================================
    //  QUERY BUILDER PERSONALIZADO
    // =======================================================================

    /**
     * Crea una nueva instancia del query builder personalizado.
     * 
     * Esto permite usar métodos personalizados en las consultas:
     * - Equipo::query()->search($term)
     * - Equipo::query()->withoutEjercicios()
     * - Equipo::query()->filtered()
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return EquipoQueryBuilder Query builder con métodos personalizados
     */
    public function newEloquentBuilder($query): EquipoQueryBuilder
    {
        return new EquipoQueryBuilder($query);
    }

    // =======================================================================
    //  RELACIONES
    // =======================================================================

    /**
     * Relación: Un equipo puede usarse en múltiples ejercicios.
     * 
     * Esta es una relación uno-a-muchos (1:N).
     * Un equipo puede estar asociado a varios ejercicios,
     * pero un ejercicio solo puede usar un equipo.
     * 
     * Ejemplos:
     * - Mancuernas → Press de banca con mancuernas, Curl de bíceps, etc.
     * - Barra olímpica → Press de banca, Sentadillas, Peso muerto, etc.
     * 
     * USO:
     * ```php
     * // Obtener ejercicios de un equipo
     * $ejercicios = $equipo->ejercicios;
     * 
     * // Contar ejercicios
     * $cantidad = $equipo->ejercicios()->count();
     * 
     * // Verificar si tiene ejercicios
     * if ($equipo->ejercicios()->exists()) {
     *     // Tiene ejercicios asociados
     * }
     * ```
     * 
     * @return HasMany
     */
    public function ejercicios(): HasMany
    {
        return $this->hasMany(Ejercicio::class, 'equipo_id');
    }

    /**
     * Relación: Usuario creador del equipo.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Scope: Filtrar equipos visibles para el usuario.
     * Muestra:
     * 1. Equipos creados por el usuario.
     * 2. Equipos creados por Administradores (globales).
     * 3. Equipos sin usuario asignado (legacy/globales).
     */
    public function scopeForUser($query, $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('usuario_id', $user->id)
              ->orWhereHas('usuario', function ($subQ) {
                  $subQ->where('tipo_usuario_id', 1); // Admin
              })
              ->orWhereNull('usuario_id');
        });
    }
}