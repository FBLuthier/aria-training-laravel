<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =======================================================================
 * MODELO: RUTINA
 * =======================================================================
 * 
 * Representa un programa de entrenamiento completo creado por un entrenador.
 * Una rutina organiza ejercicios en días específicos para alcanzar un objetivo.
 * 
 * TABLA: rutinas
 * 
 * COLUMNAS:
 * - id: int (PK, auto-increment)
 * - nombre: string(255) - Nombre de la rutina (ej: "Rutina Principiante")
 * - objetivo_id: int (FK) - Objetivo de entrenamiento
 * - usuario_id: int (FK) - Entrenador que creó la rutina
 * - estado: enum - Estado de la rutina (activo, inactivo, borrador)
 * - created_at, updated_at: timestamps
 * 
 * ESTRUCTURA DE UNA RUTINA:
 * Rutina (ej: "Hipertrofia 4 días")
 *   ├── Día 1 (Lunes - Pecho/Tríceps)
 *   │   ├── Ejercicio 1: Press de Banca (4 series x 8-12 reps)
 *   │   ├── Ejercicio 2: Aperturas (3 series x 10-15 reps)
 *   │   └── Ejercicio 3: Fondos (3 series x 10-12 reps)
 *   ├── Día 2 (Martes - Espalda/Bíceps)
 *   ├── Día 3 (Jueves - Piernas)
 *   └── Día 4 (Viernes - Hombros)
 * 
 * RELACIONES:
 * - usuario: BelongsTo - Entrenador que creó la rutina
 * - objetivo: BelongsTo - Objetivo de entrenamiento (Hipertrofia, Fuerza, etc.)
 * - dias: HasMany - Días de entrenamiento de la rutina
 * 
 * EJEMPLOS DE RUTINAS:
 * - "Rutina Principiante 3 días" (Objetivo: Acondicionamiento)
 * - "Hipertrofia Push/Pull/Legs" (Objetivo: Ganancia Muscular)
 * - "Fuerza Powerlifting 4 días" (Objetivo: Incremento de Fuerza)
 * 
 * CARACTERÍSTICAS:
 * - Factory para generar datos de prueba
 * - Eager loading optimizado (básico y completo)
 * - Estructura jerárquica: Rutina → Días → Ejercicios
 * 
 * USO:
 * ```php
 * // Crear rutina
 * $rutina = Rutina::create([
 *     'nombre' => 'Hipertrofia 4 días',
 *     'objetivo_id' => 1,
 *     'usuario_id' => auth()->id(),
 *     'estado' => 'activo'
 * ]);
 * 
 * // Cargar rutina con detalles completos
 * $rutina = Rutina::withFullDetails()->find(1);
 * 
 * // Obtener rutinas de un entrenador
 * $rutinas = $entrenador->rutinas;
 * ```
 * 
 * @property int $id
 * @property string $nombre
 * @property int $objetivo_id
 * @property int $usuario_id
 * @property string $estado
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read User $usuario
 * @property-read Objetivo $objetivo
 * @property-read \Illuminate\Database\Eloquent\Collection|RutinaDia[] $dias
 * 
 * @package App\Models
 * @since 1.0
 */
class Rutina extends Model
{
    // =======================================================================
    //  TRAITS
    // =======================================================================
    
    /** @var HasFactory Permite usar factories para testing */
    use HasFactory;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    // =======================================================================
    //  CONFIGURACIÓN DEL MODELO
    // =======================================================================

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'rutinas';

    /** @var array<string> Campos asignables en masa */
    protected $fillable = [
        'nombre',       // Nombre de la rutina
        'atleta_id',    // FK al atleta asignado
        'estado',       // activo, inactivo, borrador
    ];

    // =======================================================================
    //  RELACIONES
    // =======================================================================

    /**
     * Relación: Una rutina es asignada a un atleta.
     * 
     * @return BelongsTo
     */
    public function atleta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atleta_id');
    }

    /**
     * Relación: Una rutina tiene múltiples días de entrenamiento.
     * 
     * Los días organizan los ejercicios por sesiones de entrenamiento.
     * Cada día puede representar:
     * - Un día de la semana (Lunes, Martes, etc.)
     * - Un grupo muscular (Pecho, Espalda, etc.)
     * - Un patrón (Push, Pull, Legs)
     * 
     * Ejemplos:
     * - Rutina 3 días: Día 1, Día 2, Día 3
     * - Push/Pull/Legs: Push Day, Pull Day, Leg Day
     * - Split semanal: Lunes (Pecho), Martes (Espalda), etc.
     * 
     * Uso:
     * ```php
     * // Obtener días de la rutina
     * $dias = $rutina->dias;
     * 
     * // Crear día en rutina
     * $rutina->dias()->create([
     *     'nombre' => 'Día 1 - Pecho/Tríceps',
     *     'orden' => 1
     * ]);
     * 
     * // Contar días
     * $totalDias = $rutina->dias()->count();
     * ```
     * 
     * @return HasMany
     */
    public function dias(): HasMany
    {
        return $this->hasMany(RutinaDia::class, 'rutina_id');
    }

    // =======================================================================
    //  QUERY SCOPES
    // =======================================================================

    /**
     * Scope para cargar relaciones básicas con eager loading.
     * 
     * Carga solo el usuario (entrenador) y el objetivo de la rutina.
     * Útil para listar rutinas sin necesidad de todos los detalles.
     * 
     * Uso:
     * ```php
     * // Listar rutinas con info básica
     * $rutinas = Rutina::withRelations()->get();
     * foreach ($rutinas as $rutina) {
     *     echo "{$rutina->nombre} - {$rutina->objetivo->nombre}";
     *     echo " (Creada por: {$rutina->usuario->nombre_1})";
     * }
     * ```
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'atleta:id,nombre_1,apellido_1',
        ]);
    }

    /**
     * Scope para cargar rutina COMPLETA con toda la estructura.
     * 
     * ⚠️ ADVERTENCIA: Carga MUCHOS datos. Usar solo cuando se necesite
     * la estructura completa de la rutina (vista de detalle, impresión, etc.)
     * 
     * Carga:
     * - Usuario (entrenador)
     * - Objetivo
     * - Días de la rutina
     *   - Ejercicios de cada día
     *     - Equipo necesario
     *     - Grupos musculares trabajados
     *   - Bloques de ejercicios
     *     - Tipo de bloque
     * 
     * Uso:
     * ```php
     * // Cargar rutina completa para vista de detalle
     * $rutina = Rutina::withFullDetails()->find(1);
     * 
     * // Ahora se puede acceder a toda la estructura sin queries adicionales
     * foreach ($rutina->dias as $dia) {
     *     echo $dia->nombre;
     *     foreach ($dia->rutinaEjercicios as $ejercicio) {
     *         echo $ejercicio->ejercicio->nombre;
     *         echo $ejercicio->ejercicio->equipo->nombre;
     *     }
     * }
     * ```
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithFullDetails($query)
    {
        return $query->with([
            'usuario:id,nombre_1,apellido_1',
            'objetivo:id,nombre',
            'dias.rutinaEjercicios.ejercicio.equipo',
            'dias.rutinaEjercicios.ejercicio.gruposMusculares',
            'dias.bloques.tipoBloque'
        ]);
    }
}