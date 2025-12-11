<?php

namespace App\Livewire\Traits;

use App\Enums\SortDirection;

/**
 * =======================================================================
 * TRAIT PARA ORDENAMIENTO DE TABLAS
 * =======================================================================
 *
 * Este trait implementa la funcionalidad de ordenamiento (sorting) en tablas,
 * permitiendo al usuario hacer clic en encabezados de columnas para ordenar
 * ascendente o descendentemente.
 *
 * FUNCIONALIDADES:
 * - sortBy($field): Ordena por una columna específica
 * - Cambio automático de dirección (ASC ↔ DESC)
 * - Hooks before/after para lógica personalizada
 * - Aplica ordenamiento a queries Eloquent
 *
 * COMPORTAMIENTO:
 * - Primera vez en columna: Ordena ASC
 * - Segunda vez en misma columna: Cambia a DESC
 * - Cambio a otra columna: Vuelve a ASC
 *
 * USO EN VISTA:
 * ```blade
 * <x-sortable-header
 *     field="nombre"
 *     :currentField="$sortField"
 *     :direction="$sortDirection->value">
 *     Nombre
 * </x-sortable-header>
 * ```
 *
 * USO EN QUERY:
 * ```php
 * $query = Model::query();
 * $this->applySort($query);
 * $items = $query->get();
 * ```
 *
 * EJEMPLO COMPLETO:
 * Usuario hace clic en "Nombre" columna:
 * 1. sortBy('nombre') se ejecuta
 * 2. beforeSort() hook (opcional)
 * 3. Si ya estaba en 'nombre': cambia ASC→DESC o DESC→ASC
 * 4. Si era otra columna: establece ASC
 * 5. afterSort() hook (opcional, ej: limpiar registro resaltado)
 * 6. Vista se recarga con nuevo orden
 *
 * @since 1.0
 */
trait HasSorting
{
    // =======================================================================
    //  PROPIEDADES DE ORDENAMIENTO
    // =======================================================================

    /** @var string Columna actual por la que se ordena la tabla (default: 'id') */
    public string $sortField = 'id';

    /** @var SortDirection Dirección del ordenamiento (ASC o DESC) */
    public SortDirection $sortDirection = SortDirection::ASC;

    // =======================================================================
    //  MÉTODO PRINCIPAL
    // =======================================================================

    /**
     * Ordena la tabla por una columna específica.
     *
     * Si se hace clic en la misma columna, invierte la dirección.
     * Si se hace clic en una columna diferente, ordena ASC.
     *
     * Ejemplos:
     * - Campo actual: 'id' ASC → sortBy('nombre') → 'nombre' ASC
     * - Campo actual: 'nombre' ASC → sortBy('nombre') → 'nombre' DESC
     * - Campo actual: 'nombre' DESC → sortBy('nombre') → 'nombre' ASC
     *
     * @param  string  $field  Nombre de la columna a ordenar
     */
    public function sortBy(string $field): void
    {
        // Hook antes de ordenar (ej: limpiar resaltado)
        $this->beforeSort();

        // Si es la misma columna, invertir dirección
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection->opposite();
        } else {
            // Si es columna nueva, empezar con ASC
            $this->sortDirection = SortDirection::ASC;
        }

        // Establecer nueva columna
        $this->sortField = $field;

        // Hook después de ordenar
        $this->afterSort();
    }

    // =======================================================================
    //  HOOKS PARA PERSONALIZACIÓN
    // =======================================================================

    /**
     * Hook ejecutado ANTES de cambiar el ordenamiento.
     *
     * Úsalo para limpiar estado que depende del orden actual.
     * Por ejemplo, limpiar el registro resaltado porque
     * cambiará de posición.
     */
    protected function beforeSort(): void
    {
        // Sobrescribir en el componente si necesitas lógica adicional
        // Ejemplo: $this->clearRecentlyCreated();
    }

    /**
     * Hook ejecutado DESPUÉS de cambiar el ordenamiento.
     *
     * Úsalo para lógica adicional después del cambio.
     */
    protected function afterSort(): void
    {
        // Sobrescribir en el componente si necesitas lógica adicional
    }

    // =======================================================================
    //  APLICACIÓN A QUERIES
    // =======================================================================

    /**
     * Aplica el ordenamiento actual a una query Eloquent.
     *
     * Agrega la cláusula ORDER BY con el campo y dirección actuales.
     *
     * Uso:
     * ```php
     * $query = Equipo::query();
     * $this->applySort($query);
     * // Genera: SELECT * FROM equipos ORDER BY nombre ASC
     * ```
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  Query a modificar
     * @return \Illuminate\Database\Eloquent\Builder Query con ORDER BY aplicado
     */
    protected function applySort($query)
    {
        return $query->orderBy($this->sortField, $this->sortDirection->value);
    }
}
