<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * =======================================================================
 * MODELO: EJERCICIO
 * =======================================================================
 * 
 * Representa un ejercicio de gimnasio que puede ser incluido en rutinas.
 * Los ejercicios son los bloques fundamentales de las rutinas de entrenamiento.
 * 
 * TABLA: ejercicios
 * 
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - nombre: string(255) - Nombre del ejercicio (ej: "Press de Banca")
 * - descripcion: text|null - Descripción detallada del ejercicio
 * - equipo_id: int|null (FK) - Equipo necesario para el ejercicio
 * - estado: enum - Estado del ejercicio (activo, inactivo)
 * - created_at, updated_at: timestamps automáticos
 * 
 * RELACIONES:
 * - equipo: BelongsTo - Equipo necesario (Mancuernas, Barra, etc.)
 * - gruposMusculares: BelongsToMany - Músculos que trabaja el ejercicio
 * 
 * EJEMPLOS DE EJERCICIOS:
 * - Press de Banca (Equipo: Barra, Músculos: Pecho, Tríceps)
 * - Curl de Bíceps (Equipo: Mancuernas, Músculos: Bíceps)
 * - Sentadillas (Equipo: Barra, Músculos: Cuádriceps, Glúteos)
 * 
 * CARACTERÍSTICAS:
 * - Factory para generar datos de prueba
 * - Eager loading optimizado con scopeWithRelations
 * - Relación N:M con GrupoMuscular (un ejercicio trabaja varios músculos)
 * 
 * USO:
 * ```php
 * // Crear ejercicio
 * $ejercicio = Ejercicio::create([
 *     'nombre' => 'Press de Banca',
 *     'descripcion' => 'Ejercicio para pecho',
 *     'equipo_id' => 1,
 *     'estado' => 'activo'
 * ]);
 * 
 * // Asignar grupos musculares
 * $ejercicio->gruposMusculares()->attach([1, 2]); // Pecho y Tríceps
 * 
 * // Consultar con relaciones
 * $ejercicios = Ejercicio::withRelations()->get();
 * ```
 * 
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int|null $equipo_id
 * @property string $estado
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Equipo|null $equipo
 * @property-read \Illuminate\Database\Eloquent\Collection|GrupoMuscular[] $gruposMusculares
 * 
 * @package App\Models
 * @since 1.0
 */
class Ejercicio extends Model
{
    // =======================================================================
    //  TRAITS
    // =======================================================================
    
    /** @var HasFactory Permite usar factories para testing */
    /** @var HasFactory Permite usar factories para testing */
    use HasFactory;
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
            $q->where('nombre', 'like', '%' . $search . '%')
              ->orWhere('descripcion', 'like', '%' . $search . '%');
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
        // Mapeo de campos de ordenamiento si es necesario
        // Si sortField es una relación, aquí se manejaría. Por ahora es simple.
        return $query->applyFilters($search, $showingTrash)
            ->orderBy($sortField, $sortDirection);
    }

    // =======================================================================
    //  CONFIGURACIÓN DEL MODELO
    // =======================================================================

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'ejercicios';

    /** @var array<string> Campos asignables en masa */
    protected $fillable = [
        'nombre',        // Nombre del ejercicio
        'descripcion',   // Descripción detallada
        'equipo_id',     // FK al equipo necesario (opcional)
        'grupo_muscular_id', // FK al músculo principal
        'url_video',     // Nueva columna
        'estado',        // Estado: activo, inactivo
        'usuario_id',    // Creador del ejercicio
    ];

    // ... (existing code) ...

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    /**
     * Relación: Músculo Principal (1:N)
     */
    public function grupoMuscular(): BelongsTo
    {
        return $this->belongsTo(GrupoMuscular::class, 'grupo_muscular_id');
    }

    /**
     * Relación: Un ejercicio trabaja múltiples grupos musculares.
     * 
     * Esta es una relación muchos-a-muchos (N:M).
     * Un ejercicio puede trabajar varios músculos, y un músculo puede
     * ser trabajado por varios ejercicios.
     * 
     * TABLA PIVOT: ejercicios_x_grupo_muscular
     * 
     * Ejemplos:
     * - Press de Banca → Pecho, Hombros, Tríceps
     * - Sentadillas → Cuádriceps, Glúteos, Isquiotibiales
     * - Curl de Bíceps → Bíceps
     * 
     * Uso:
     * ```php
     * // Asignar músculos a un ejercicio
     * $ejercicio->gruposMusculares()->attach([1, 2, 3]);
     * 
     * // Obtener músculos del ejercicio
     * $musculos = $ejercicio->gruposMusculares;
     * 
     * // Sincronizar (reemplaza todos)
     * $ejercicio->gruposMusculares()->sync([1, 2]);
     * ```
     * 
     * @return BelongsToMany
     */
    public function gruposMusculares(): BelongsToMany
    {
        return $this->belongsToMany(
            GrupoMuscular::class,
            'ejercicios_x_grupo_muscular',
            'ejercicio_id',
            'grupo_muscular_id'
        );
    }

    // =======================================================================
    //  QUERY SCOPES
    // =======================================================================

    /**
     * Scope para cargar todas las relaciones con eager loading.
     * 
     * Previene el problema N+1 al cargar ejercicios con sus relaciones.
     * Solo carga las columnas necesarias para optimizar memoria.
     * 
     * Uso:
     * ```php
     * // Sin eager loading (N+1 queries)
     * $ejercicios = Ejercicio::all();
     * foreach ($ejercicios as $ejercicio) {
     *     echo $ejercicio->equipo->nombre; // Query por cada ejercicio
     * }
     * 
     * // Con eager loading (3 queries total)
     * $ejercicios = Ejercicio::withRelations()->get();
     * foreach ($ejercicios as $ejercicio) {
     *     echo $ejercicio->equipo->nombre; // Ya cargado
     * }
     * ```
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'equipo:id,nombre',              // Solo ID y nombre del equipo
            'gruposMusculares:id,nombre'     // Solo ID y nombre de músculos
        ]);
    }

    /**
     * Relación: Usuario creador del ejercicio.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Scope: Filtrar ejercicios visibles para el usuario.
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