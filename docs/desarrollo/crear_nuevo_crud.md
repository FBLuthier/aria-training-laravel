# Guía: Crear un Nuevo CRUD

Esta guía te llevará paso a paso para crear un módulo CRUD completo en ~30-60 minutos.

---

## 📋 Requisitos Previos

Antes de empezar, asegúrate de tener:
- Modelo creado y migración ejecutada
- Factory del modelo (para testing)
- Políticas de autorización configuradas

---

## 🚀 Pasos para Crear un CRUD

### Paso 1: Crear el Form

El Form encapsula la lógica de validación y guardado.

**Ubicación:** `app/Livewire/Forms/NuevoModeloForm.php`

```php
<?php

namespace App\Livewire\Forms;

use App\Models\NuevoModelo;
use Livewire\Attributes\Validate;
use Livewire\Form;

class NuevoModeloForm extends Form
{
    public ?NuevoModelo $modelo = null;

    #[Validate('required|string|max:255')]
    public string $nombre = '';

    #[Validate('nullable|string|max:1000')]
    public string $descripcion = '';

    /**
     * Configura el formulario con un modelo existente (para edición).
     */
    public function setModelo(NuevoModelo $modelo): void
    {
        $this->modelo = $modelo;
        $this->nombre = $modelo->nombre;
        $this->descripcion = $modelo->descripcion ?? '';
    }

    /**
     * Guarda el modelo (create o update).
     */
    public function save(): string
    {
        $this->validate();

        $isCreating = is_null($this->modelo);

        if ($isCreating) {
            $this->modelo = NuevoModelo::create($this->only(['nombre', 'descripcion']));
        } else {
            $this->modelo->update($this->only(['nombre', 'descripcion']));
        }

        return $isCreating ? 'created' : 'updated';
    }

    /**
     * Resetea el formulario.
     */
    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->modelo = null;
        $this->nombre = '';
        $this->descripcion = '';
    }
}
```

---

### Paso 2: Crear el Query Builder (Opcional pero Recomendado)

El Query Builder elimina duplicación de queries.

**Ubicación:** `app/Models/Builders/NuevoModeloQueryBuilder.php`

```php
<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class NuevoModeloQueryBuilder extends Builder
{
    /**
     * Filtra por término de búsqueda.
     */
    public function search(?string $search): self
    {
        if (empty($search)) {
            return $this;
        }

        return $this->where('nombre', 'like', "%{$search}%");
    }

    /**
     * Aplica filtro de papelera.
     */
    public function trash(bool $showTrash = false): self
    {
        return $showTrash ? $this->onlyTrashed() : $this;
    }

    /**
     * Ordena por campo y dirección.
     */
    public function sortBy(string $field = 'id', string $direction = 'asc'): self
    {
        return $this->orderBy($field, $direction);
    }

    /**
     * Aplica filtros comunes de búsqueda y papelera.
     */
    public function applyFilters(?string $search = null, bool $showTrash = false): self
    {
        return $this->search($search)->trash($showTrash);
    }

    /**
     * Aplica filtros y ordenamiento (método todo-en-uno).
     */
    public function filtered(
        ?string $search = null,
        bool $showTrash = false,
        string $sortField = 'id',
        string $sortDirection = 'asc'
    ): self {
        return $this
            ->search($search)
            ->trash($showTrash)
            ->sortBy($sortField, $sortDirection);
    }

    /**
     * Solo registros activos.
     */
    public function active(): self
    {
        return $this->whereNull('deleted_at');
    }

    /**
     * Obtiene IDs como array.
     */
    public function getIds(): array
    {
        return $this->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }
}
```

---

### Paso 3: Conectar el Query Builder al Modelo

**Ubicación:** Actualiza `app/Models/NuevoModelo.php`

```php
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

## ✅ Checklist de Implementación

- [ ] Form creado con validaciones
- [ ] Query Builder creado y conectado al modelo
- [ ] Componente Livewire creado con todos los traits
- [ ] Vista Blade creada con componentes reutilizables
- [ ] Ruta agregada en `routes/web.php`
- [ ] Políticas de autorización configuradas
- [ ] Traducciones agregadas (si aplica)
- [ ] Tests creados
- [ ] Documentación actualizada

---

## 🎯 Resultado

Con esta estructura, habrás creado un CRUD completo con:
- ✅ Create, Read, Update, Delete
- ✅ Soft Delete con papelera
- ✅ Restore y Force Delete
- ✅ Búsqueda en tiempo real
- ✅ Ordenamiento de columnas
- ✅ Selección múltiple y acciones en lote
- ✅ Auditoría automática
- ✅ Autorización en cada acción
- ✅ UI consistente con el resto del sistema
- ✅ Código reutilizable y mantenible

**Tiempo estimado: 30-60 minutos** 🚀
