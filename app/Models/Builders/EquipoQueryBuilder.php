<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * =======================================================================
 * QUERY BUILDER PERSONALIZADO PARA EQUIPOS
 * =======================================================================
 *
 * Este Query Builder personaliza las consultas al modelo Equipo,
 * agregando métodos específicos del dominio y heredando funcionalidad
 * común del trait BaseQueryBuilder.
 *
 * MÉTODOS HEREDADOS DE BaseQueryBuilder:
 * - search($search): Busca en campos configurables
 * - trash($showTrash): Filtra por eliminados o activos
 * - sortBy($field, $direction): Ordena resultados
 * - filtered(): Aplica búsqueda, filtro de trash y ordenamiento
 * - active(): Solo registros activos
 * - dateRange(): Filtra por rango de fechas
 * - recent(): Registros recientes
 *
 * MÉTODOS PROPIOS:
 * - withEjercicios(): Equipos que tienen ejercicios
 * - withoutEjercicios(): Equipos sin ejercicios
 * - whereEjerciciosCount(): Filtra por cantidad de ejercicios
 *
 * USO EN MODELO:
 * ```php
 * // En Equipo.php
 * public function newEloquentBuilder($query): EquipoQueryBuilder
 * {
 *     return new EquipoQueryBuilder($query);
 * }
 * ```
 *
 * USO EN CONSULTAS:
 * ```php
 * // Búsqueda con filtros
 * Equipo::query()->filtered('Mancuernas', false, 'nombre', 'asc')->get();
 *
 * // Equipos sin usar
 * Equipo::query()->withoutEjercicios()->get();
 *
 * // Equipos populares (con muchos ejercicios)
 * Equipo::query()->whereEjerciciosCount('>=', 5)->get();
 * ```
 *
 * BENEFICIOS:
 * - Código más legible y expresivo
 * - Queries reutilizables
 * - Búsqueda consistente en toda la app
 *
 * @since 1.0
 */
class EquipoQueryBuilder extends Builder
{
    use BaseQueryBuilder;

    // =======================================================================
    //  CONFIGURACIÓN
    // =======================================================================

    /**
     * Campos en los que se realizarán las búsquedas.
     *
     * El método search() heredado de BaseQueryBuilder busca en estos campos
     * usando LIKE '%término%'.
     *
     * @var array<string> Lista de nombres de columnas
     */
    protected array $searchableFields = ['nombre'];

    // =======================================================================
    //  MÉTODOS PERSONALIZADOS (SI NECESITAS LÓGICA ESPECIAL)
    // =======================================================================

    /**
     * Filtra equipos que tienen al menos un ejercicio asociado.
     */
    public function withEjercicios(): self
    {
        return $this->has('ejercicios');
    }

    /**
     * Filtra equipos que NO tienen ejercicios asociados.
     * Útil para identificar equipos sin usar.
     */
    public function withoutEjercicios(): self
    {
        return $this->doesntHave('ejercicios');
    }

    /**
     * Carga la relación de ejercicios con el equipo.
     */
    public function withEjerciciosRelation(): self
    {
        return $this->with('ejercicios');
    }

    /**
     * Filtra equipos con un número específico de ejercicios.
     *
     * @param  string  $operator  Operador de comparación ('=', '>', '<', etc.)
     * @param  int  $count  Número de ejercicios
     */
    public function whereEjerciciosCount(string $operator, int $count): self
    {
        return $this->has('ejercicios', $operator, $count);
    }

    // NOTA: Los siguientes métodos ya NO son necesarios porque vienen de BaseQueryBuilder:
    // - search($search)
    // - trash($showTrash)
    // - sortBy($field, $direction)
    // - applyFilters($search, $showTrash)
    // - filtered($search, $showTrash, $sortField, $sortDirection)
    // - active()
    // - getIds()
    // - dateRange($field, $from, $to)
    // - recent($days, $field)
    // - exceptIds($ids)
    // - onlyIds($ids)
}
