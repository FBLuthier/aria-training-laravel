# Guía: Crear un Nuevo CRUD

Esta guía te llevará paso a paso para crear un módulo CRUD completo en **~30-60 minutos** usando los **componentes base reutilizables** del sistema.

---

## ⚡ NOVEDAD: Componentes Base (v1.4)

Desde la versión 1.4, el sistema incluye **3 componentes base** que reducen el código en **70-95%**:

| Componente | Propósito | Beneficio |
|------------|-----------|-----------|
| **BaseModelForm** | Lógica común de Forms | 70% menos código |
| **BaseQueryBuilder** | Consultas reutilizables | 60% menos código |
| **BaseAdminPolicy** | Autorización estándar | 95% menos código |

📚 **Documentación completa:** [`docs/arquitectura/componentes_base.md`](../arquitectura/componentes_base.md)

---

## 📋 Requisitos Previos

Antes de empezar, asegúrate de tener:
- ✅ Modelo creado y migración ejecutada
- ✅ Factory del modelo (para testing)
- ✅ Comprensión básica de los componentes base

---

## 🚀 Pasos para Crear un CRUD

### Paso 1: Crear el Form (usando BaseModelForm)

El Form ahora extiende de **BaseModelForm** para heredar funcionalidad común.

**Ubicación:** `app/Livewire/Forms/NuevoModeloForm.php`

```php
<?php

namespace App\Livewire\Forms;

use App\Models\NuevoModelo;
use Illuminate\Validation\Rule;

/**
 * Formulario para gestionar NuevoModelo.
 * Extiende BaseModelForm para heredar funcionalidad común.
 */
class NuevoModeloForm extends BaseModelForm
{
    // =======================================================================
    //  PROPIEDADES DEL FORMULARIO
    // =======================================================================
    
    public string $nombre = '';
    public string $descripcion = '';
    
    // =======================================================================
    //  MÉTODOS ABSTRACTOS REQUERIDOS
    // =======================================================================
    
    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('nuevo_modelos')->ignore($this->model?->id)
            ],
            'descripcion' => 'nullable|string|max:1000',
        ];
    }
    
    protected function getModelClass(): string
    {
        return NuevoModelo::class;
    }
    
    protected function fillFromModel($model): void
    {
        $this->nombre = $model->nombre;
        $this->descripcion = $model->descripcion ?? '';
    }
    
    protected function getModelData(): array
    {
        return [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ];
    }
    
    // =======================================================================
    //  MÉTODOS DE CONVENIENCIA (OPCIONAL)
    // =======================================================================
    
    /**
     * Método de conveniencia para compatibilidad.
     */
    public function setNuevoModelo(NuevoModelo $modelo): void
    {
        $this->setModel($modelo);
    }
}
```

**✨ Ventajas:**
- ✅ Método `save()` ya implementado
- ✅ Método `reset()` ya implementado  
- ✅ Validación automática
- ✅ Hooks disponibles (beforeValidation, beforeSave, afterSave)
- ✅ Métodos helper (isEditing, isCreating)

---

### Paso 2: Crear el Query Builder (usando BaseQueryBuilder)

El Query Builder ahora usa el **trait BaseQueryBuilder** para heredar funcionalidad común.

**Ubicación:** `app/Models/Builders/NuevoModeloQueryBuilder.php`

```php
<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * Query Builder personalizado para NuevoModelo.
 * Usa BaseQueryBuilder trait para funcionalidad común.
 */
class NuevoModeloQueryBuilder extends Builder
{
    use BaseQueryBuilder;
    
    // =======================================================================
    //  CONFIGURACIÓN
    // =======================================================================
    
    /**
     * Campos buscables (requerido por BaseQueryBuilder).
     */
    protected array $searchableFields = ['nombre', 'descripcion'];
    
    // =======================================================================
    //  MÉTODOS PERSONALIZADOS (OPCIONAL)
    // =======================================================================
    
    /**
     * Filtra por algún criterio específico del modelo.
     * Ejemplo: si tu modelo tiene un campo "activo"
     */
    public function activos(): self
    {
        return $this->where('activo', true);
    }
}
```

**✨ Métodos heredados automáticamente:**
- ✅ `search($search)` - Busca en campos configurados
- ✅ `trash($showTrash)` - Filtro de papelera
- ✅ `sortBy($field, $direction)` - Ordenamiento
- ✅ `filtered($search, $showTrash, $sortField, $sortDirection)` - Todo-en-uno
<?php

namespace App\Models;

use App\Models\Builders\NuevoModeloQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NuevoModelo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Crea una nueva instancia del query builder personalizado.
     */
    public function newEloquentBuilder($query): NuevoModeloQueryBuilder
    {
        return new NuevoModeloQueryBuilder($query);
    }
}
```

---

### Paso 4: Crear el Componente Livewire

**Ubicación:** `app/Livewire/Admin/GestionarNuevosModelos.php`

```php
<?php

namespace App\Livewire\Admin;

use App\Actions\DeleteModelAction;
use App\Actions\ForceDeleteModelAction;
use App\Actions\RestoreModelAction;
use App\Livewire\Forms\NuevoModeloForm;
use App\Livewire\Traits\WithAuditLogging;
use App\Livewire\Traits\WithBulkActions;
use App\Livewire\Traits\WithCrudOperations;
use App\Models\NuevoModelo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GestionarNuevosModelos extends Component
{
    use WithPagination, WithBulkActions, WithCrudOperations, WithAuditLogging;
    
    // =======================================================================
    //  CONSTANTES
    // =======================================================================
    
    /** Número de registros por página */
    private const PER_PAGE = 10;
    
    // =======================================================================
    //  PROPIEDADES
    // =======================================================================
    
    public string $search = '';
    public ?NuevoModelo $modeloRecienCreado = null;
    public NuevoModeloForm $form;
    
    // Propiedades para bulk actions
    public bool $confirmingBulkDelete = false;
    public bool $confirmingBulkRestore = false;
    public bool $confirmingBulkForceDelete = false;
    
    // =======================================================================
    //  LIFECYCLE HOOKS
    // =======================================================================
    
    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->modeloRecienCreado = null;
    }
    
    // =======================================================================
    //  COMPUTED PROPERTIES
    // =======================================================================
    
    #[Computed]
    public function modelos()
    {
        return NuevoModelo::query()
            ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
            ->paginate(self::PER_PAGE);
    }
    
    #[Computed]
    public function totalFilteredCount(): int
    {
        return NuevoModelo::query()
            ->applyFilters($this->search, $this->showingTrash)
            ->count();
    }
    
    // =======================================================================
    //  MÉTODOS REQUERIDOS POR WithCrudOperations
    // =======================================================================
    
    protected function getModelClass(): string
    {
        return NuevoModelo::class;
    }
    
    protected function setFormModel($model): void
    {
        $this->form->setModelo($model);
    }
    
    protected function auditFormSave(?array $oldValues): void
    {
        $this->auditSave($this->form->modelo, $oldValues);
    }
    
    // =======================================================================
    //  MÉTODOS OPCIONALES PARA RESALTADO
    // =======================================================================
    
    protected function markAsRecentlyCreated($model): void
    {
        $this->modeloRecienCreado = $model;
    }
    
    protected function clearRecentlyCreated(): void
    {
        $this->modeloRecienCreado = null;
    }
    
    protected function beforeSort(): void
    {
        $this->clearRecentlyCreated();
    }
    
    // =======================================================================
    //  BULK ACTIONS
    // =======================================================================
    
    protected function getFilteredQuery()
    {
        return NuevoModelo::query()->applyFilters($this->search, $this->showingTrash);
    }
    
    protected function selectAllItems(): void
    {
        $modelos = NuevoModelo::query()
            ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
            ->paginate(self::PER_PAGE);

        $this->selectedItems = $modelos->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }
    
    protected function getSelectedModels(bool $withTrashed = false)
    {
        $query = $this->getFilteredQuery();
        
        if ($withTrashed) {
            $query->withTrashed();
        }
        
        if ($this->selectingAll) {
            if (count($this->exceptItems) > 0) {
                $query->whereNotIn('id', $this->exceptItems);
            }
        } else {
            $query->whereIn('id', $this->selectedItems);
        }

        return $query->get();
    }
    
    public function deleteSelected(): void
    {
        $modelos = $this->getSelectedModels();
        $result = app(DeleteModelAction::class)->executeBulk($modelos);
        
        $this->confirmingBulkDelete = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }
    
    public function restoreSelected(): void
    {
        $modelos = $this->getSelectedModels(withTrashed: true);
        $result = app(RestoreModelAction::class)->executeBulk($modelos);
        
        $this->confirmingBulkRestore = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }
    
    public function forceDeleteSelected(): void
    {
        $modelos = $this->getSelectedModels(withTrashed: true);
        $result = app(ForceDeleteModelAction::class)->executeBulk($modelos);
        
        $this->confirmingBulkForceDelete = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }
    
    // =======================================================================
    //  RENDER
    // =======================================================================
    
    public function render()
    {
        $this->authorize('viewAny', NuevoModelo::class);
        return view('livewire.admin.gestionar-nuevos-modelos');
    }
}
```

---

### Paso 5: Crear la Vista Blade

**Ubicación:** `resources/views/livewire/admin/gestionar-nuevos-modelos.blade.php`

```blade
<div>
    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gestionar Nuevos Modelos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- BARRA DE ACCIONES --}}
                    <div class="flex justify-between items-center mb-4">
                        {{-- Búsqueda --}}
                        <div class="w-1/3">
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text" 
                                placeholder="Buscar..." 
                                class="w-full px-4 py-2 border rounded-lg"
                            >
                        </div>

                        {{-- Botones de acción --}}
                        <div class="flex gap-2">
                            @can('create', App\Models\NuevoModelo::class)
                                <button 
                                    wire:click="create" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                                >
                                    Crear Nuevo
                                </button>
                            @endcan

                            <button 
                                wire:click="toggleTrash" 
                                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
                            >
                                {{ $showingTrash ? 'Ver Activos' : 'Ver Papelera' }}
                            </button>
                        </div>
                    </div>

                    {{-- COMPONENTE DE BULK ACTIONS --}}
                    @if(count($selectedItems) > 0 || $selectingAll)
                        <x-bulk-actions-bar
                            :selectedCount="$this->selectedCount"
                            :confirmingDelete="$confirmingBulkDelete"
                            :confirmingRestore="$confirmingBulkRestore"
                            :confirmingForceDelete="$confirmingBulkForceDelete"
                            :showingTrash="$showingTrash"
                        />
                    @endif

                    {{-- TABLA --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <input 
                                            wire:model.live="selectAll" 
                                            type="checkbox" 
                                            class="w-4 h-4"
                                        >
                                    </th>
                                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('id')">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('nombre')">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Descripción
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->modelos as $modelo)
                                    <x-table-row-highlight 
                                        wireKey="modelo-{{ $modelo->id }}"
                                        :highlighted="$modeloRecienCreado?->id === $modelo->id"
                                    >
                                        <x-table-checkbox :value="$modelo->id" />
                                        
                                        <td class="px-6 py-4">{{ $modelo->id }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $modelo->nombre }}</td>
                                        <td class="px-6 py-4">{{ Str::limit($modelo->descripcion, 50) }}</td>
                                        
                                        <x-table-actions>
                                            @if($showingTrash)
                                                @can('restore', $modelo)
                                                    <x-action-button :action="'restore('.$modelo->id.')'" color="green" icon>
                                                        Restaurar
                                                    </x-action-button>
                                                @endcan
                                                @can('forceDelete', $modelo)
                                                    <x-action-button :action="'forceDelete('.$modelo->id.')'" color="red" icon>
                                                        Eliminar Permanente
                                                    </x-action-button>
                                                @endcan
                                            @else
                                                @can('update', $modelo)
                                                    <x-action-button :action="'edit('.$modelo->id.')'" color="blue" icon>
                                                        Editar
                                                    </x-action-button>
                                                @endcan
                                                @can('delete', $modelo)
                                                    <x-action-button :action="'delete('.$modelo->id.')'" color="red" icon>
                                                        Eliminar
                                                    </x-action-button>
                                                @endcan
                                            @endif
                                        </x-table-actions>
                                    </x-table-row-highlight>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">
                                            No se encontraron registros
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINACIÓN --}}
                    <div class="mt-4">
                        {{ $this->modelos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODALES --}}
    <x-modal-form wire:model="showFormModal" title="Formulario de Modelo">
        <form wire:submit="save">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Nombre *</label>
                    <input 
                        wire:model="form.nombre" 
                        type="text" 
                        class="mt-1 block w-full rounded-md border-gray-300"
                    >
                    @error('form.nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Descripción</label>
                    <textarea 
                        wire:model="form.descripcion" 
                        rows="3" 
                        class="mt-1 block w-full rounded-md border-gray-300"
                    ></textarea>
                    @error('form.descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" wire:click="closeFormModal" class="px-4 py-2 bg-gray-300 rounded">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                    Guardar
                </button>
            </div>
        </form>
    </x-modal-form>

    {{-- Resto de modales de confirmación... --}}
</div>
```

---

## 🎨 Paso 6: Mejorar la Experiencia de Usuario (UX) - v1.5

**⭐ NUEVO:** Agrega feedback visual profesional con loading states y notificaciones toast.

### 6.1. Agregar Loading States

**En el campo de búsqueda:**
```blade
<div class="relative w-full">
    <x-text-input 
        wire:model.live="search"
        placeholder="Buscar..." 
    />
    <div class="absolute right-3 top-1/2 -translate-y-1/2">
        <x-spinner 
            size="sm" 
            color="gray"
            wire:loading 
            wire:target="search"
            style="display: none;"
        />
    </div>
</div>
```

**En la tabla (mientras carga datos):**
```blade
{{-- Loading state --}}
<x-loading-state 
    target="search,toggleTrash,sortBy,gotoPage" 
    message="Cargando registros..."
    class="my-4"
/>

{{-- Tabla (se oculta durante carga) --}}
<div wire:loading.remove wire:target="search,toggleTrash,sortBy,gotoPage">
    <table>
        {{-- Contenido de la tabla --}}
    </table>
</div>
```

**En botones con acciones:**
```blade
{{-- Botones con loading automático --}}
<x-primary-button wire:click="save" loadingTarget="save">
    Guardar
</x-primary-button>

<x-secondary-button wire:click="toggleTrash" loadingTarget="toggleTrash">
    {{ $showingTrash ? 'Ver Activos' : 'Ver Papelera' }}
</x-secondary-button>

<x-danger-button wire:click="performDelete" loadingTarget="performDelete">
    Eliminar
</x-danger-button>
```

**Para operaciones masivas (overlay de pantalla completa):**
```blade
{{-- Al final de la vista, antes de </div> --}}
<x-loading-overlay 
    target="deleteSelected,restoreSelected,forceDeleteSelected"
    message="Procesando registros seleccionados..."
/>
```

### 6.2. Agregar Notificaciones Toast

**En tu componente Livewire (PHP):**
```php
public function save(): void
{
    $this->form->validate();
    
    try {
        $oldValues = $this->form->model?->exists ? $this->form->model->toArray() : null;
        $this->form->save();
        
        $message = $this->form->model->wasRecentlyCreated 
            ? 'Registro creado exitosamente.' 
            : 'Registro actualizado exitosamente.';
            
        // ⭐ Notificación de éxito
        $this->dispatch('notify', message: $message, type: 'success');
        
        $this->closeFormModal();
        $this->auditFormSave($oldValues);
        
    } catch (\Exception $e) {
        // ⭐ Notificación de error
        $this->dispatch('notify', 
            message: 'Error al guardar: ' . $e->getMessage(), 
            type: 'error',
            duration: 7000 // Duración más larga para errores
        );
        
        Log::error('Error al guardar modelo: ' . $e->getMessage());
    }
}

public function performDelete(): void
{
    $action = app(DeleteModelAction::class);
    $result = $action->execute($this->deletingModel);
    
    // ⭐ Notificación según resultado
    if ($result['success']) {
        $this->dispatch('notify', message: $result['message'], type: 'success');
    } else {
        $this->dispatch('notify', message: $result['message'], type: 'error');
    }
    
    $this->closeConfirmationModal();
    $this->deletingId = null;
}

public function deleteSelected(): void
{
    $count = count($this->selectedItems);
    
    if ($count === 0) {
        // ⭐ Notificación de advertencia
        $this->dispatch('notify', 
            message: 'No hay registros seleccionados.', 
            type: 'warning'
        );
        return;
    }
    
    $modelos = $this->getSelectedModels();
    $action = app(DeleteModelAction::class);
    $result = $action->executeBulk($modelos);
    
    // ⭐ Notificación de éxito masivo
    $this->dispatch('notify', 
        message: "{$count} registros eliminados correctamente.", 
        type: 'success'
    );
    
    $this->clearSelections();
}
```

**Tipos de notificaciones disponibles:**
- `success`: Para operaciones exitosas (verde)
- `error`: Para errores y fallos (rojo)
- `warning`: Para advertencias (amarillo)
- `info`: Para información general (azul)

**Duración personalizada:**
- Mensajes cortos de éxito: 3000-4000ms (default)
- Mensajes importantes/errores: 7000-10000ms
- Sin auto-dismiss (requiere acción): 0ms

### 6.3. Resultado Final

Con estos ajustes, tu CRUD tendrá:
- ✅ Spinners durante búsqueda en tiempo real
- ✅ Loading states al cambiar de página o filtrar
- ✅ Botones que se deshabilitan automáticamente durante operaciones
- ✅ Feedback visual inmediato con "Procesando..."
- ✅ Overlay para operaciones masivas que previene interacciones
- ✅ Notificaciones elegantes para cada acción
- ✅ Experiencia de usuario profesional sin código complejo

**Documentación completa:**
- `docs/desarrollo/guias/loading_states.md` - Guía exhaustiva de loading states
- `docs/desarrollo/guias/toast_notifications.md` - Guía exhaustiva de toast notifications

---

## ✅ Checklist de Implementación

### Funcionalidad Base
- [ ] Form creado con validaciones
- [ ] Query Builder creado y conectado al modelo
- [ ] Componente Livewire creado con todos los traits
- [ ] Vista Blade creada con componentes reutilizables
- [ ] Ruta agregada en `routes/web.php`
- [ ] Políticas de autorización configuradas

### UX y Feedback Visual (v1.5) ⭐
- [ ] Loading states agregados (spinner en búsqueda, loading-state en tabla)
- [ ] Botones actualizados con prop `loadingTarget`
- [ ] Loading overlay agregado para operaciones masivas
- [ ] Notificaciones toast implementadas en todas las acciones
- [ ] Mensajes de error con toast type='error'
- [ ] Mensajes de advertencia con toast type='warning'

### Testing y Documentación
- [ ] Traducciones agregadas (si aplica)
- [ ] Tests creados
- [ ] Documentación actualizada

---

## 🎯 Resultado

Con esta estructura, habrás creado un CRUD completo con:

**Funcionalidad:**
- ✅ Create, Read, Update, Delete
- ✅ Soft Delete con papelera
- ✅ Restore y Force Delete
- ✅ Búsqueda en tiempo real
- ✅ Ordenamiento de columnas
- ✅ Selección múltiple y acciones en lote
- ✅ Auditoría automática
- ✅ Autorización en cada acción

**Experiencia de Usuario (v1.5):** ⭐
- ✅ Spinners durante operaciones asíncronas
- ✅ Loading states en tablas y filtros
- ✅ Overlay para operaciones masivas
- ✅ Notificaciones toast elegantes (4 tipos)
- ✅ Prevención de doble-click automática
- ✅ Feedback visual inmediato en todas las acciones
- ✅ Experiencia profesional y pulida

**Código:**
- ✅ UI consistente con el resto del sistema
- ✅ Código reutilizable y mantenible
- ✅ Componentes modulares fáciles de mantener

**Tiempo estimado: 30-60 minutos (funcionalidad base) + 5-10 minutos (UX)** 🚀
