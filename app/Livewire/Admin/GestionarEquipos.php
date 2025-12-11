<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseCrudComponent;
use App\Livewire\Forms\EquipoForm;
use App\Livewire\Traits\WithExport;
use App\Models\Equipo;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

/**
 * Componente para gestionar Equipos.
 *
 * Extiende de BaseCrudComponent para heredar toda la funcionalidad común de CRUD.
 * Solo define la configuración específica del modelo Equipo.
 *
 * ANTES: 321 líneas de código
 * AHORA: ~70 líneas (reducción del 78%)
 *
 * FUNCIONALIDADES:
 * - CRUD completo (heredado de BaseCrudComponent)
 * - Exportación Excel/CSV/PDF (WithExport trait)
 * - Búsqueda y ordenamiento
 * - Bulk actions
 * - Papelera (soft deletes)
 */
#[Layout('layouts.app')]
class GestionarEquipos extends BaseCrudComponent
{
    use WithExport;
    // =======================================================================
    //  PROPIEDADES ESPECÍFICAS
    // =======================================================================

    /** @var EquipoForm Formulario para crear/editar equipos */
    public EquipoForm $form;

    /** @var ?Equipo Equipo recién creado (para resaltado en UI) */
    public ?Equipo $equipoRecienCreado = null;

    /** @var array Listeners de eventos específicos */
    protected $listeners = [
        'equipoDeleted' => '$refresh',
        'equipoRestored' => '$refresh',
    ];

    // =======================================================================
    //  IMPLEMENTACIÓN DE MÉTODOS ABSTRACTOS
    // =======================================================================

    /**
     * Retorna la clase del modelo.
     */
    protected function getModelClass(): string
    {
        return Equipo::class;
    }

    /**
     * Retorna el nombre de la vista.
     */
    protected function getViewName(): string
    {
        return 'livewire.admin.gestionar-equipos';
    }

    // =======================================================================
    //  MÉTODOS ESPECÍFICOS (OPCIONAL)
    // =======================================================================

    /**
     * Sobrescribe setFormModel para usar el método específico setEquipo.
     * NOTA: Esto es opcional, solo si tu Form tiene un método específico.
     */
    protected function setFormModel($model): void
    {
        $this->form->setEquipo($model);
    }

    // =======================================================================
    //  CONFIGURACIÓN DE EXPORTACIÓN
    // =======================================================================

    /**
     * Columnas a exportar.
     */
    protected function getExportColumns(): array
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'created_at' => 'Fecha Creación',
            'updated_at' => 'Última Actualización',
        ];
    }

    /**
     * Formato de valores para exportación.
     */
    protected function formatExportValue($item, string $column): mixed
    {
        return match ($column) {
            'created_at', 'updated_at' => formatDateTime($item->$column),
            default => parent::formatExportValue($item, $column),
        };
    }

    /**
     * Título del PDF.
     */
    protected function getPdfTitle(): string
    {
        return 'Reporte de Equipos de Gimnasio';
    }

    // NOTA: Los siguientes métodos ahora se heredan de BaseCrudComponent:
    // - clearSelections()
    // - updatingSearch()
    // - updatingPage()
    // - selectAllItems()
    // - totalFilteredCount()
    // - getFilteredQuery()
    // - getSelectedModels()
    // - confirmDeleteSelected(), deleteSelected()
    // - confirmRestoreSelected(), restoreSelected()
    // - confirmForceDeleteSelected(), forceDeleteSelected()
    /**
     * Obtiene los items paginados con filtros aplicados.
     */
    #[Computed]
    public function items()
    {
        return Equipo::forUser(auth()->user())
            ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
            ->paginate($this->getPerPage());
    }

    // NOTA: Los siguientes métodos ahora se heredan de BaseCrudComponent:
    // - clearSelections()
}
