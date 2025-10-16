<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GestionarAuditoria extends Component
{
    use WithPagination;

    // Propiedades básicas
    public string $search = '';
    public string $actionFilter = '';
    public string $modelFilter = '';
    public ?int $userFilter = null;
    public string $startDate = '';
    public string $endDate = '';
    public ?int $detailId = null;

    // Propiedades de ordenamiento
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Propiedades del modal de exportación
    public bool $showExportModal = false;
    public array $exportOptions = [
        'fecha' => true,
        'usuario' => true,
        'accion' => true,
        'modelo' => true,
        'id_registro' => true,
        'valores_anteriores' => true,
        'valores_nuevos' => true,
        'navegador' => true,
        'sistema_operativo' => true,
        'user_agent_completo' => false
    ];
    public string $exportFormat = 'csv';

    /**
     * Inicializa el componente.
     */
    public function mount(): void
    {
        $this->resetExportOptions();
    }
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Cambia el ordenamiento.
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    /**
     * Muestra/oculta detalles de un log.
     */
    public function showDetailsFor(int $logId): void
    {
        if ($this->detailId === $logId) {
            $this->detailId = null;
        } else {
            $this->detailId = $logId;
        }
    }

    /**
     * Limpia todos los filtros.
     */
    public function clearFilters(): void
    {
        $this->search = '';
        $this->actionFilter = '';
        $this->modelFilter = '';
        $this->userFilter = null;
        $this->startDate = '';
        $this->endDate = '';
        $this->detailId = null;
        $this->resetPage();
        $this->dispatch('notify', message: 'Filtros limpiados correctamente.', type: 'success');
    }

    /**
     * Abre el modal de opciones de exportación.
     */
    public function openExportModal(): void
    {
        $this->showExportModal = true;
        $this->dispatch('export-modal-opened');
    }

    /**
     * Cierra el modal de opciones de exportación.
     */
    public function closeExportModal(): void
    {
        $this->showExportModal = false;
        $this->dispatch('export-modal-closed');
    }

    /**
     * Resetea las opciones de exportación a valores por defecto.
     */
    public function resetExportOptions(): void
    {
        $this->exportOptions = [
            'fecha' => true,
            'usuario' => true,
            'accion' => true,
            'modelo' => true,
            'id_registro' => true,
            'ip_address' => true,
            'valores_anteriores' => true,
            'valores_nuevos' => true,
            'navegador' => true,
            'sistema_operativo' => true,
            'user_agent_completo' => false
        ];
        $this->exportFormat = 'csv';
    }

    /**
     * Exporta el CSV con las opciones seleccionadas.
     */
    public function exportWithOptions(): void
    {
        $this->showExportModal = false;

        // Construir parámetros con opciones de formato y campos
        $params = array_merge([
            'format' => $this->exportFormat
        ], request()->query->all(), $this->exportOptions);

        // Redirigir según el formato seleccionado
        switch ($this->exportFormat) {
            case 'excel':
                $this->redirect(route('admin.auditoria.export', array_merge($params, ['format' => 'excel'])));
                break;
            case 'pdf':
                $this->redirect(route('admin.auditoria.export', array_merge($params, ['format' => 'pdf'])));
                break;
            default:
                $this->redirect(route('admin.auditoria.export', $params));
                break;
        }
    }

    /**
     * Renderiza la vista.
     */
    public function render()
    {
        // La autorización ya se verifica en el middleware de la ruta
        // $this->authorize('viewAny', AuditLog::class);

        $query = AuditLog::query()
            ->withRelations() // Usar scope para eager loading
            ->when($this->search, function($q) {
                $q->where(function($query) {
                    $query->where('action', 'like', '%' . $this->search . '%')
                          ->orWhere('model_type', 'like', '%' . $this->search . '%')
                          ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                          ->orWhereHas('user', function($q) {
                              $q->where('nombre_1', 'like', '%' . $this->search . '%')
                                ->orWhere('apellido_1', 'like', '%' . $this->search . '%')
                                ->orWhere('correo', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->actionFilter, fn($q) => $q->where('action', $this->actionFilter))
            ->when($this->modelFilter, fn($q) => $q->where('model_type', 'like', '%' . $this->modelFilter . '%'))
            ->when($this->userFilter, fn($q) => $q->where('user_id', $this->userFilter))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $actions = ['create' => 'Crear', 'update' => 'Actualizar', 'delete' => 'Eliminar', 'restore' => 'Restaurar', 'force_delete' => 'Eliminar Permanentemente'];
        $models = AuditLog::distinct('model_type')->pluck('model_type')->filter()->values();
        $users = User::select('id', 'nombre_1', 'apellido_1')->orderBy('nombre_1')->get();

        return view('livewire.admin.gestionar-auditoria', [
            'auditLogs' => $query,
            'actions' => $actions,
            'models' => $models,
            'users' => $users,
            'exportFormat' => $this->exportFormat,
        ]);
    }
}
