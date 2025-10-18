# Componentes Reutilizables del Sistema

Este documento describe todos los componentes reutilizables disponibles en el sistema y cómo utilizarlos.

---

## 🎯 Propósito

El sistema utiliza una arquitectura modular con componentes reutilizables para:
- **Evitar duplicación de código**
- **Mantener consistencia** entre módulos
- **Acelerar desarrollo** de nuevas funcionalidades
- **Facilitar mantenimiento** y testing

---

## 📦 Actions (Acciones de Negocio)

Las Actions encapsulan lógica de negocio reutilizable para operaciones CRUD.

### DeleteModelAction

**Ubicación:** `app/Actions/DeleteModelAction.php`

**Propósito:** Eliminar modelos (soft delete) con auditoría automática.

**Uso:**
```php
use App\Actions\DeleteModelAction;

// Eliminar un modelo
$result = app(DeleteModelAction::class)->execute($modelo);

// Eliminar múltiples modelos
$result = app(DeleteModelAction::class)->executeBulk($modelos);
```

**Beneficios:**
- Auditoría automática
- Verificación de autorización
- Manejo consistente de errores
- Mensajes de éxito estandarizados

---

### RestoreModelAction

**Ubicación:** `app/Actions/RestoreModelAction.php`

**Propósito:** Restaurar modelos desde la papelera con auditoría.

**Uso:**
```php
use App\Actions\RestoreModelAction;

$result = app(RestoreModelAction::class)->execute($modelo);
```

---

### ForceDeleteModelAction

**Ubicación:** `app/Actions/ForceDeleteModelAction.php`

**Propósito:** Eliminar permanentemente modelos con auditoría.

**Uso:**
```php
use App\Actions\ForceDeleteModelAction;

$result = app(ForceDeleteModelAction::class)->execute($modelo);
```

---

## 🧩 Traits (Funcionalidad Compartida)

Los Traits proporcionan funcionalidad común que puede incluirse en cualquier componente Livewire.

### WithCrudOperations

**Ubicación:** `app/Livewire/Traits/WithCrudOperations.php`

**Propósito:** Proporciona operaciones CRUD completas (create, edit, delete, restore, forceDelete).

**Incluye automáticamente:**
- `HasFormModal` - Gestión de formularios
- `HasSorting` - Ordenamiento de tablas
- `HasTrashToggle` - Toggle de papelera

**Uso:**
```php
use App\Livewire\Traits\WithCrudOperations;

class GestionarModelo extends Component
{
    use WithCrudOperations;
    
    // Debes implementar estos 3 métodos:
    protected function getModelClass(): string
    {
        return Modelo::class;
    }
    
    protected function setFormModel($model): void
    {
        $this->form->setModelo($model);
    }
    
    protected function auditFormSave(?array $oldValues): void
    {
        $this->auditSave($this->form->modelo, $oldValues);
    }
}
```

**Métodos que obtienes automáticamente:**
- `create()` - Abre modal para crear
- `edit($id)` - Abre modal para editar
- `save()` - Guarda (create o update)
- `delete($id)` - Elimina (con confirmación)
- `restore($id)` - Restaura desde papelera
- `forceDelete($id)` - Elimina permanentemente
- `sortBy($field)` - Ordena tabla
- `toggleTrash()` - Cambia entre vista activa y papelera

**Beneficio:** Reduce un componente CRUD de ~500 líneas a ~50 líneas.

---

### WithAuditLogging

**Ubicación:** `app/Livewire/Traits/WithAuditLogging.php`

**Propósito:** Centralizar toda la lógica de auditoría del sistema.

**Uso:**
```php
use App\Livewire\Traits\WithAuditLogging;

class MiComponente extends Component
{
    use WithAuditLogging;
    
    public function save(): void
    {
        $oldValues = $this->modelo->exists ? $this->modelo->toArray() : null;
        $this->modelo->save();
        
        // Audita automáticamente (detecta si es create o update)
        $this->auditSave($this->modelo, $oldValues);
    }
}
```

**Métodos disponibles:**
```php
$this->auditCreate($modelo);                    // Para creaciones
$this->auditUpdate($modelo, $valoresAnteriores); // Para actualizaciones
$this->auditDelete($modelo, $valoresModelo);     // Para eliminaciones
$this->auditRestore($modelo, $valoresModelo);    // Para restauraciones
$this->auditForceDelete($modelo, $valoresModelo); // Para eliminaciones permanentes
$this->auditSave($modelo, $valoresAnteriores);   // Detecta automáticamente
```

**Beneficio:** Consistencia garantizada en auditoría, elimina código duplicado.

---

### WithBulkActions

**Ubicación:** `app/Livewire/Traits/WithBulkActions.php`

**Propósito:** Proporciona funcionalidad de selección múltiple y acciones en lote.

**Uso:**
```php
use App\Livewire\Traits\WithBulkActions;

class GestionarModelo extends Component
{
    use WithBulkActions;
    
    public function deleteSelected(): void
    {
        $modelos = $this->getSelectedModels();
        app(DeleteModelAction::class)->executeBulk($modelos);
        $this->clearSelections();
    }
}
```

**Propiedades disponibles:**
- `$selectedItems` - Array de IDs seleccionados
- `$selectingAll` - Si está en modo "seleccionar todos"
- `$exceptItems` - IDs excluidos en modo "seleccionar todos"

**Métodos disponibles:**
- `selectAllRecords()` - Activa modo "seleccionar todos"
- `selectOnlyPage()` - Vuelve a seleccionar solo página actual
- `clearSelections()` - Limpia todas las selecciones

**Beneficio:** Gestión eficiente de selecciones masivas, optimizado para miles de registros.

---

## 🔍 Query Builders Personalizados

Los Query Builders proporcionan métodos fluidos para construir queries complejas de forma legible.

### EquipoQueryBuilder

**Ubicación:** `app/Models/Builders/EquipoQueryBuilder.php`

**Propósito:** Queries reutilizables para el modelo Equipo.

**Métodos disponibles:**
```php
// Métodos individuales
->search(?string $search)          // Busca por nombre
->trash(bool $showTrash)           // Filtra papelera
->sortBy(string $field, string $direction) // Ordena resultados
->active()                         // Solo registros activos

// Métodos combinados
->applyFilters(?string $search, bool $showTrash)  // Búsqueda + papelera
->filtered($search, $showTrash, $sortField, $sortDirection) // Todo en uno

// Helpers
->getIds()                         // Obtiene array de IDs
```

**Uso:**
```php
// En lugar de esto:
$equipos = Equipo::query()
    ->when($this->search, fn($q) => $q->where('nombre', 'like', '%' . $this->search . '%'))
    ->when($this->showingTrash, fn($q) => $q->onlyTrashed())
    ->orderBy($this->sortField, $this->sortDirection->value)
    ->paginate(10);

// Usa esto:
$equipos = Equipo::query()
    ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
    ->paginate(10);
```

**Beneficio:** Elimina duplicación de queries, código más legible y testeable.

**Cómo crear uno nuevo:**
```php
// 1. Crear el builder
class NuevoQueryBuilder extends Builder
{
    public function search(?string $search): self
    {
        if (empty($search)) return $this;
        return $this->where('campo', 'like', "%{$search}%");
    }
    
    public function applyFilters(?string $search = null, bool $showTrash = false): self
    {
        return $this->search($search)->trash($showTrash);
    }
}

// 2. Conectarlo al modelo
class NuevoModelo extends Model
{
    public function newEloquentBuilder($query): NuevoQueryBuilder
    {
        return new NuevoQueryBuilder($query);
    }
}
```

---

## 🎨 Componentes Blade

Componentes de UI reutilizables para mantener consistencia visual.

### 📦 Componentes de Loading States (v1.5)

Sistema completo de indicadores de carga para mejorar la experiencia de usuario durante operaciones asíncronas.

#### spinner

**Ubicación:** `resources/views/components/spinner.blade.php`

**Propósito:** Indicador giratorio de carga SVG escalable.

**Props:**
- `size`: Tamaño (`xs`, `sm`, `md`, `lg`, `xl`). Default: `md`
- `color`: Color (`current`, `white`, `gray`, `primary`, `red`). Default: `current`

**Uso:**
```blade
{{-- Spinner básico --}}
<x-spinner size="sm" color="gray" />

{{-- Con wire:loading para mostrar durante operación --}}
<x-spinner 
    size="sm" 
    color="primary"
    wire:loading 
    wire:target="save"
    style="display: none;"
/>

{{-- En input de búsqueda --}}
<div class="relative">
    <input wire:model.live="search" />
    <div class="absolute right-3 top-1/2 -translate-y-1/2">
        <x-spinner size="sm" wire:loading wire:target="search" />
    </div>
</div>
```

**Beneficio:** Feedback visual inmediato, 5 tamaños y 5 colores para diferentes contextos.

---

#### loading-overlay

**Ubicación:** `resources/views/components/loading-overlay.blade.php`

**Propósito:** Overlay de pantalla completa para operaciones largas que bloquean la interfaz.

**Props:**
- `message`: Mensaje a mostrar. Default: `"Cargando..."`
- `target`: Target específico de Livewire (opcional)

**Uso:**
```blade
{{-- Overlay básico --}}
<x-loading-overlay message="Procesando operación..." />

{{-- Con target específico --}}
<x-loading-overlay 
    target="deleteSelected,restoreSelected"
    message="Procesando registros seleccionados..."
/>
```

**Beneficio:** Previene interacciones durante operaciones críticas, proporciona contexto al usuario.

---

#### loading-state

**Ubicación:** `resources/views/components/loading-state.blade.php`

**Propósito:** Componente para mostrar estado de carga inline o en bloque.

**Props:**
- `target`: Target de Livewire (opcional)
- `message`: Mensaje a mostrar. Default: `"Cargando..."`
- `inline`: Boolean para modo inline. Default: `false`

**Uso:**
```blade
{{-- Loading state block para tabla --}}
<x-loading-state 
    target="search,toggleTrash,sortBy" 
    message="Cargando equipos..."
/>

{{-- Loading state inline --}}
<x-loading-state 
    target="search" 
    message="Buscando..."
    inline
/>
```

**Beneficio:** Estados de carga flexibles para diferentes contextos de UI.

---

### 🔔 Componentes de Notificaciones Toast (v1.5)

Sistema completo de notificaciones tipo toast para feedback inmediato al usuario.

#### toast-container

**Ubicación:** `resources/views/components/toast-container.blade.php`

**Propósito:** Contenedor principal que maneja todas las notificaciones toast con Alpine.js.

**Características:**
- 4 tipos: `success`, `error`, `warning`, `info`
- Auto-dismiss configurable con barra de progreso
- Apilamiento inteligente de múltiples notificaciones
- Animaciones suaves con Alpine.js transitions
- Cierre manual con botón X

**Uso:**
```blade
{{-- Ya incluido en layouts/app.blade.php, no necesitas agregarlo manualmente --}}
<x-toast-container />
```

**Desde Livewire (PHP):**
```php
// Notificación de éxito
$this->dispatch('notify', message: 'Operación exitosa', type: 'success');

// Notificación de error
$this->dispatch('notify', message: 'Error al procesar', type: 'error');

// Con duración personalizada (ms)
$this->dispatch('notify', message: 'Mensaje largo', type: 'info', duration: 7000);

// Sin auto-dismiss
$this->dispatch('notify', message: 'Requiere acción', type: 'warning', duration: 0);
```

**Desde JavaScript:**
```javascript
// Helpers globales disponibles
notify('Mensaje', 'success', 4000);
notifySuccess('Operación exitosa');
notifyError('Error crítico');
notifyWarning('Advertencia');
notifyInfo('Información');
```

**Beneficio:** Feedback visual elegante y no intrusivo, mejora significativa en UX.

---

#### toast-trigger

**Ubicación:** `resources/views/components/toast-trigger.blade.php`

**Propósito:** Helper para disparar toasts desde session flash después de redirecciones.

**Props:**
- `key`: Key de sesión. Default: `'toast'`
- `messageKey`: Key del mensaje. Default: `'message'`
- `typeKey`: Key del tipo. Default: `'type'`

**Uso:**
```blade
{{-- En tu vista Blade --}}
<x-toast-trigger />

{{-- En el controlador --}}
return redirect()->route('home')->with([
    'toast' => true,
    'message' => 'Operación exitosa',
    'type' => 'success'
]);
```

**Beneficio:** Notificaciones persistentes entre redirecciones.

---

### 🎯 Componentes Mejorados con Loading States (v1.5)

Los botones del sistema ahora incluyen soporte automático para loading states.

#### primary-button, secondary-button, danger-button

**Nueva prop:**
- `loadingTarget`: Target de Livewire para mostrar estado de carga automáticamente

**Uso:**
```blade
{{-- Botón con loading automático --}}
<x-primary-button wire:click="save" loadingTarget="save">
    Guardar
</x-primary-button>

{{-- Durante la operación, el botón muestra:
     1. Spinner
     2. Texto "Procesando..."
     3. Se deshabilita automáticamente
--}}
```

**Beneficio:** Prevención de doble-click, feedback visual automático, código más limpio.

---

### 📋 Componentes de Tabla

#### table-checkbox

**Ubicación:** `resources/views/components/table-checkbox.blade.php`

**Propósito:** Checkbox estilizado para tablas con selección múltiple.

**Uso:**
```blade
<x-table-checkbox :value="$modelo->id" />

{{-- Con modelo personalizado --}}
<x-table-checkbox :value="$id" model="otrosItems" />
```

---

#### table-actions

**Ubicación:** `resources/views/components/table-actions.blade.php`

**Propósito:** Contenedor para botones de acción en tablas.

**Uso:**
```blade
<x-table-actions>
    <x-action-button :action="'edit('.$modelo->id.')'" color="blue" icon>
        Editar
    </x-action-button>
    <x-action-button :action="'delete('.$modelo->id.')'" color="red" icon>
        Eliminar
    </x-action-button>
</x-table-actions>
```

---

#### action-button

**Ubicación:** `resources/views/components/action-button.blade.php`

**Propósito:** Botón de acción con spinner de carga automático.

**Uso:**
```blade
{{-- Botón simple --}}
<x-action-button :action="'edit('.$id.')'" color="blue">
    Editar
</x-action-button>

{{-- Con icono de carga --}}
<x-action-button :action="'delete('.$id.')'" color="red" icon>
    Eliminar
</x-action-button>
```

**Colores disponibles:** `blue`, `red`, `green`, `yellow`

---

#### table-row-highlight

**Ubicación:** `resources/views/components/table-row-highlight.blade.php`

**Propósito:** Fila de tabla con resaltado condicional (útil para registros recién creados).

**Uso:**
```blade
<x-table-row-highlight wireKey="modelo-{{ $modelo->id }}" :highlighted="$esNuevo">
    <td>{{ $modelo->nombre }}</td>
    <td>{{ $modelo->descripcion }}</td>
</x-table-row-highlight>
```

---

## 🚀 Computed Properties (Livewire v3)

Las Computed Properties proporcionan caché automático para mejorar el rendimiento.

**Cuándo usar:**
```php
#[Computed]
public function metodo()
{
    // ✅ Retorna datos calculados
    // ✅ Sin efectos secundarios
    // ✅ Usado múltiples veces en la vista
}
```

**Ejemplo:**
```php
#[Computed]
public function totalRegistros(): int
{
    return Modelo::count();
}

// En vista: {{ $this->totalRegistros }} (sin paréntesis)
```

**Beneficio:** Resultado se calcula una vez y se cachea durante el render, reducción significativa de queries repetidas.

---

## 📝 Constantes del Sistema

Usa constantes en lugar de valores mágicos para mejor mantenibilidad.

**Ejemplo:**
```php
class GestionarModelo extends Component
{
    /** Número de registros por página */
    private const PER_PAGE = 10;
    
    /** Campo de ordenamiento por defecto */
    private const DEFAULT_SORT_FIELD = 'id';
    
    public function render()
    {
        return Modelo::query()->paginate(self::PER_PAGE); // ✅ Claro
        // vs
        return Modelo::query()->paginate(10); // ❌ ¿Qué es 10?
    }
}
```

---

## 📊 Resumen de Componentes

| Componente | Tipo | Propósito | Beneficio Principal |
|------------|------|-----------|---------------------|
| DeleteModelAction | Action | Eliminar con auditoría | Consistencia |
| RestoreModelAction | Action | Restaurar con auditoría | Reutilización |
| ForceDeleteModelAction | Action | Eliminar permanentemente | Centralización |
| WithCrudOperations | Trait | CRUD completo | Reduce 90% código |
| WithAuditLogging | Trait | Auditoría centralizada | Consistencia garantizada |
| WithBulkActions | Trait | Selección múltiple | Optimizado para miles de registros |
| EquipoQueryBuilder | Builder | Queries reutilizables | DRY, legibilidad |
| **spinner** | **Blade (v1.5)** | **Indicador de carga** | **5 tamaños, 5 colores** |
| **loading-overlay** | **Blade (v1.5)** | **Overlay pantalla completa** | **Operaciones largas** |
| **loading-state** | **Blade (v1.5)** | **Estado de carga** | **Inline o bloque** |
| **toast-container** | **Blade (v1.5)** | **Notificaciones toast** | **4 tipos con auto-dismiss** |
| **toast-trigger** | **Blade (v1.5)** | **Toast desde sesión** | **Persistencia en redirecciones** |
| table-checkbox | Blade | Checkbox estilizado | UI consistente |
| table-actions | Blade | Contenedor acciones | UI consistente |
| action-button | Blade | Botón con spinner | UX mejorada |
| table-row-highlight | Blade | Fila con resaltado | Feedback visual |

**Total: 18 componentes reutilizables** (5 nuevos en v1.5)

---

## 🎯 Próximos Pasos

- **Para crear un CRUD nuevo:** Ver `desarrollo/crear_nuevo_crud.md`
- **Para entender por qué usamos estos patrones:** Ver `desarrollo/buenas_practicas.md`
- **Para setup inicial:** Ver `desarrollo/guia_inicio_rapido.md`
