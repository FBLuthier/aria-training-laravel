<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

/**
 * =======================================================================
 * COMPONENTE: GESTIONAR AUDITORÍA
 * =======================================================================
 * 
 * Componente Livewire para visualizar y filtrar el registro completo
 * de auditoría del sistema. Muestra todas las acciones realizadas
 * por los usuarios para trazabilidad, seguridad y cumplimiento normativo.
 * 
 * FUNCIONALIDADES:
 * - Visualización de logs de auditoría paginados
 * - Búsqueda por usuario, acción, modelo, IP
 * - Filtros por: acción, modelo, usuario, rango de fechas
 * - Ordenamiento por cualquier columna
 * - Vista de detalles expandibles (valores old/new)
 * - Exportación a Excel/CSV/PDF con opciones personalizables
 * - Limpieza de filtros
 * 
 * FILTROS DISPONIBLES:
 * - Búsqueda general (nombre usuario, email, IP, tipo modelo, acción)
 * - Por acción: create, update, delete, restore, force_delete
 * - Por modelo: Equipo, Usuario, Ejercicio, etc.
 * - Por usuario específico
 * - Por rango de fechas
 * 
 * USO:
 * Accesible solo para administradores en la ruta /admin/auditoria
 * 
 * SEGURIDAD:
 * - Requiere autenticación
 * - Requiere rol de administrador
 * - Verificación en middleware de ruta
 * 
 * @package App\Livewire\Admin
 * @since 1.0
 */
#[Layout('layouts.app')]
class GestionarAuditoria extends Component
{
    use WithPagination;

    // =======================================================================
    //  PROPIEDADES DE BÚSQUEDA Y FILTROS
    // =======================================================================
    
    /** @var string Búsqueda general en múltiples campos */
    public string $search = '';
    
    /** @var string Filtro por tipo de acción (create, update, delete, etc.) */
    public string $actionFilter = '';
    
    /** @var string Filtro por tipo de modelo (App\Models\Equipo, etc.) */
    public string $modelFilter = '';
    
    /** @var int|null Filtro por usuario específico */
    public ?int $userFilter = null;
    
    /** @var string Fecha inicial del rango */
    public string $startDate = '';
    
    /** @var string Fecha final del rango */
    public string $endDate = '';
    
    /** @var int|null ID del log cuyos detalles se están mostrando */
    public ?int $detailId = null;

    // =======================================================================
    //  PROPIEDADES DE ORDENAMIENTO
    // =======================================================================
    
    /** @var string Campo por el que se ordena (default: created_at) */
    public string $sortField = 'created_at';
    
    /** @var string Dirección del ordenamiento (asc o desc) */
    public string $sortDirection = 'desc';

    // =======================================================================
    //  PROPIEDADES DE EXPORTACIÓN
    // =======================================================================
    
    /** @var bool Controla visibilidad del modal de exportación */
    public bool $showExportModal = false;
    
    /** 
     * @var array Opciones de qué columnas exportar
     * Permite al usuario personalizar qué datos incluir en la exportación
     */
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
    
    /** @var string Formato de exportación (csv, excel, pdf) */
    public string $exportFormat = 'csv';
    
    // =======================================================================
    //  LIFECYCLE HOOKS
    // =======================================================================

    /**
     * Inicializa el componente al montarse.
     * 
     * @return void
     */
    public function mount(): void
    {
        // Seguridad: Solo administradores pueden ver auditoría
        if (!auth()->user()->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $this->resetExportOptions();
    }
    
    /**
     * Hook que se ejecuta al actualizar la búsqueda.
     * Resetea la paginación para volver a página 1.
     * 
     * @return void
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // =======================================================================
    //  MÉTODOS DE ORDENAMIENTO
    // =======================================================================

    /**
     * Cambia el ordenamiento de la tabla.
     * 
     * Si se hace clic en la misma columna, invierte la dirección.
     * Si se hace clic en una columna diferente, ordena ASC.
     * 
     * @param string $field Nombre del campo a ordenar
     * @return void
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

    // =======================================================================
    //  MÉTODOS DE VISUALIZACIÓN
    // =======================================================================

    /**
     * Muestra u oculta los detalles completos de un log.
     * 
     * Al hacer clic expande la fila para mostrar:
     * - Valores anteriores (old_values)
     * - Valores nuevos (new_values)
     * - User agent completo
     * 
     * @param int $logId ID del log a expandir/colapsar
     * @return void
     */
    public function showDetailsFor(int $logId): void
    {
        if ($this->detailId === $logId) {
            $this->detailId = null; // Cerrar si ya está abierto
        } else {
            $this->detailId = $logId; // Abrir detalles
        }
    }

    // =======================================================================
    //  MÉTODOS DE FILTROS
    // =======================================================================

    /**
     * Limpia todos los filtros aplicados.
     * 
     * Resetea a valores por defecto y vuelve a página 1.
     * 
     * @return void
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

    // =======================================================================
    //  MÉTODOS DE EXPORTACIÓN
    // =======================================================================

    /**
     * Abre el modal de opciones de exportación.
     * 
     * @return void
     */
    public function openExportModal(): void
    {
        $this->showExportModal = true;
        $this->dispatch('export-modal-opened');
    }

    /**
     * Cierra el modal de opciones de exportación.
     * 
     * @return void
     */
    public function closeExportModal(): void
    {
        $this->showExportModal = false;
        $this->dispatch('export-modal-closed');
    }

    /**
     * Resetea las opciones de exportación a valores por defecto.
     * 
     * @return void
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
     * Exporta los logs con las opciones seleccionadas.
     * 
     * Redirige a la ruta de exportación según el formato elegido.
     * 
     * @return void
     */
    public function exportWithOptions(): void
    {
        $this->showExportModal = false;

        // Construir parámetros con opciones
        $params = array_merge([
            'format' => $this->exportFormat
        ], request()->query->all(), $this->exportOptions);

        // Redirigir según formato
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

    // =======================================================================
    //  RENDER
    // =======================================================================

    /**
     * Renderiza la vista del componente.
     * 
     * Aplica todos los filtros y paginación a la consulta de logs.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Construir query con filtros
        $query = AuditLog::query()
            ->withRelations()
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

        // Datos para filtros
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
