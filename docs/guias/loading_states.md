# Sistema de Loading States (Estados de Carga)

## 📋 Descripción General

El sistema de loading states proporciona feedback visual al usuario durante operaciones asíncronas, mejorando significativamente la experiencia de usuario (UX) al indicar que una acción está en proceso.

## 🎨 Componentes Disponibles

### 1. Spinner (`<x-spinner>`)

Componente base para mostrar un indicador giratorio de carga.

**Ubicación:** `resources/views/components/spinner.blade.php`

**Props:**
- `size`: Tamaño del spinner (`xs`, `sm`, `md`, `lg`, `xl`). Default: `md`
- `color`: Color del spinner (`current`, `white`, `gray`, `primary`, `red`). Default: `current`

**Ejemplos de uso:**

```blade
{{-- Spinner pequeño con color actual --}}
<x-spinner size="sm" color="current" />

{{-- Spinner mediano blanco --}}
<x-spinner size="md" color="white" />

{{-- Spinner grande primario --}}
<x-spinner size="lg" color="primary" />

{{-- Con wire:loading --}}
<x-spinner 
    size="sm" 
    color="gray"
    wire:loading 
    wire:target="search"
/>
```

### 2. Loading Overlay (`<x-loading-overlay>`)

Overlay de pantalla completa para operaciones largas que bloquean la interfaz.

**Ubicación:** `resources/views/components/loading-overlay.blade.php`

**Props:**
- `message`: Mensaje a mostrar. Default: `"Cargando..."`
- `target`: Target específico de Livewire (opcional)

**Ejemplos de uso:**

```blade
{{-- Loading overlay simple --}}
<x-loading-overlay message="Procesando operación..." />

{{-- Loading overlay con target específico --}}
<x-loading-overlay 
    target="deleteSelected,restoreSelected,forceDeleteSelected"
    message="Procesando operación..."
/>
```

### 3. Loading State (`<x-loading-state>`)

Componente para mostrar estado de carga inline o en bloque.

**Ubicación:** `resources/views/components/loading-state.blade.php`

**Props:**
- `target`: Target de Livewire (opcional)
- `message`: Mensaje a mostrar. Default: `"Cargando..."`
- `inline`: Boolean para modo inline. Default: `false`

**Ejemplos de uso:**

```blade
{{-- Loading state block para tabla --}}
<x-loading-state 
    target="search,toggleTrash,sortBy" 
    message="Cargando equipos..."
    class="my-4"
/>

{{-- Loading state inline --}}
<x-loading-state 
    target="search" 
    message="Buscando..."
    inline
/>
```

### 4. Botones Mejorados

Los componentes de botones ahora soportan loading states automáticos.

**Componentes actualizados:**
- `<x-primary-button>`
- `<x-secondary-button>`
- `<x-danger-button>`

**Nueva prop:**
- `loadingTarget`: Target de Livewire para mostrar estado de carga

**Ejemplos de uso:**

```blade
{{-- Botón primario con loading --}}
<x-primary-button wire:click="save" loadingTarget="save">
    Guardar
</x-primary-button>

{{-- Botón de peligro con loading --}}
<x-danger-button wire:click="performDelete" loadingTarget="performDelete">
    Eliminar
</x-danger-button>

{{-- Botón secundario con loading --}}
<x-secondary-button wire:click="toggleTrash" loadingTarget="toggleTrash">
    Ver Papelera
</x-secondary-button>
```

## 🔧 Implementación en Componentes Livewire

### Ejemplo: Campo de Búsqueda con Spinner

```blade
<div class="relative w-full">
    <x-text-input 
        wire:model.live="search"
        class="block w-full" 
        type="text" 
        placeholder="Buscar..." />
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

### Ejemplo: Botón de Acción con Loading

```blade
<button 
    wire:click="edit({{ $item->id }})" 
    class="font-medium text-blue-600 hover:underline inline-flex items-center gap-1"
>
    <x-spinner 
        size="xs" 
        color="current" 
        wire:loading 
        wire:target="edit({{ $item->id }})" 
        style="display: none;" 
    />
    <span>Editar</span>
</button>
```

### Ejemplo: Tabla con Loading State

```blade
{{-- Loading state --}}
<x-loading-state 
    target="search,sortBy,gotoPage" 
    message="Cargando datos..."
    class="my-4"
/>

{{-- Tabla --}}
<div wire:loading.remove wire:target="search,sortBy,gotoPage">
    <x-data-table>
        {{-- Contenido de la tabla --}}
    </x-data-table>
</div>
```

### Ejemplo: Operación con Overlay

```blade
{{-- Al final de la vista --}}
<x-loading-overlay 
    target="deleteSelected,restoreSelected"
    message="Procesando operación..."
/>
```

## 📍 Implementaciones Actuales

### Gestión de Equipos (`gestionar-equipos.blade.php`)

**Loading states implementados:**

1. **Búsqueda:** Spinner en el input de búsqueda
2. **Toggle Papelera:** Loading en botón "Ver Papelera/Ver Activos"
3. **Tabla:** Loading state completo mientras se cargan los datos
4. **Acciones de fila:** Spinners en botones Editar/Eliminar/Restaurar
5. **Modales de confirmación:** Loading en todos los botones de acción
6. **Overlay:** Para operaciones en lote (eliminar, restaurar, force delete)

**Targets utilizados:**
- `search` - Búsqueda en tiempo real
- `toggleTrash` - Cambio de vista
- `sortBy` - Ordenamiento de columnas
- `gotoPage, previousPage, nextPage` - Paginación
- `edit, delete, restore, forceDelete` - Acciones individuales
- `performDelete, performRestore, performForceDelete` - Confirmaciones
- `deleteSelected, restoreSelected, forceDeleteSelected` - Acciones en lote

### Gestión de Auditoría (`gestionar-auditoria.blade.php`)

**Loading states implementados:**

1. **Búsqueda general:** Spinner en el input
2. **Botones de acción:** Loading en botones Exportar y Limpiar Filtros
3. **Tabla:** Loading state mientras se filtran/cargan registros
4. **Ver detalles:** Spinner en botón de detalles
5. **Modal de exportación:** Loading en botón de exportar
6. **Overlay:** Para proceso de exportación

**Targets utilizados:**
- `search` - Búsqueda general
- `actionFilter, modelFilter, userFilter, startDate, endDate` - Filtros
- `clearFilters` - Limpiar filtros
- `sortBy` - Ordenamiento
- `gotoPage, previousPage, nextPage` - Paginación
- `showDetailsFor` - Mostrar/ocultar detalles
- `exportWithOptions` - Exportación
- `openExportModal` - Abrir modal de exportación

## 🎯 Mejores Prácticas

### 1. Siempre Especifica el Target

```blade
{{-- ✅ BIEN: Target específico --}}
<x-spinner wire:loading wire:target="save" />

{{-- ❌ MAL: Sin target (se mostrará en cualquier acción) --}}
<x-spinner wire:loading />
```

### 2. Usa Loading States Apropiados

```blade
{{-- Para operaciones rápidas: Spinner inline --}}
<x-spinner size="xs" wire:loading wire:target="edit({{ $id }})" />

{{-- Para operaciones lentas: Loading state block --}}
<x-loading-state target="search" message="Buscando..." />

{{-- Para operaciones muy lentas: Overlay --}}
<x-loading-overlay target="export" message="Exportando datos..." />
```

### 3. Combina con wire:loading.remove

```blade
{{-- Oculta el contenido mientras carga --}}
<div wire:loading.remove wire:target="search">
    {{-- Contenido original --}}
</div>

<x-loading-state target="search" message="Cargando..." />
```

### 4. Deshabilita Botones Durante la Carga

```blade
<button
    wire:click="action"
    wire:loading.attr="disabled"
    wire:target="action"
    class="disabled:opacity-50"
>
    Acción
</button>
```

### 5. Agrupa Targets Relacionados

```blade
{{-- Targets múltiples separados por coma --}}
<x-loading-state 
    target="search,filter,sort,paginate" 
    message="Actualizando datos..."
/>
```

## 🚀 Agregar Loading States a Nuevos Componentes

### Paso 1: Identificar Acciones Asíncronas

Identifica todas las acciones que hacen llamadas al servidor:
- Búsquedas
- Filtros
- Ordenamiento
- Paginación
- CRUD (Crear, Leer, Actualizar, Eliminar)
- Exportaciones
- Carga de datos relacionados

### Paso 2: Añadir Loading States

```blade
{{-- 1. Campo de input con spinner --}}
<div class="relative">
    <input wire:model.live="field" />
    <x-spinner 
        size="sm" 
        wire:loading 
        wire:target="field" 
        class="absolute right-3 top-1/2 -translate-y-1/2"
    />
</div>

{{-- 2. Botón con loading --}}
<x-primary-button 
    wire:click="action" 
    loadingTarget="action"
>
    Acción
</x-primary-button>

{{-- 3. Tabla con loading state --}}
<x-loading-state target="action" message="Cargando..." />
<div wire:loading.remove wire:target="action">
    {{-- Tabla --}}
</div>

{{-- 4. Overlay para operaciones largas --}}
<x-loading-overlay 
    target="longAction" 
    message="Procesando..."
/>
```

### Paso 3: Personalizar Mensajes

Usa mensajes descriptivos y específicos:

```blade
{{-- ✅ BIEN: Mensajes específicos --}}
<x-loading-state message="Buscando equipos..." />
<x-loading-overlay message="Eliminando registros seleccionados..." />

{{-- ❌ MAL: Mensajes genéricos --}}
<x-loading-state message="Cargando..." />
<x-loading-overlay message="Espere..." />
```

## 🎨 Personalización de Estilos

### Cambiar Colores del Spinner

Edita `resources/views/components/spinner.blade.php`:

```php
$colorClasses = [
    'current' => 'text-current',
    'white' => 'text-white',
    'gray' => 'text-gray-600 dark:text-gray-400',
    'primary' => 'text-indigo-600 dark:text-indigo-400',
    'red' => 'text-red-600 dark:text-red-400',
    'custom' => 'text-blue-500', // Añade colores personalizados
];
```

### Personalizar Loading Overlay

Edita `resources/views/components/loading-overlay.blade.php` para cambiar el fondo, tamaño, o animación.

## 📊 Beneficios Implementados

1. **Feedback Visual Inmediato:** El usuario sabe que su acción fue reconocida
2. **Prevención de Doble Click:** Los botones se deshabilitan durante la carga
3. **Mejor UX:** Reduce la frustración e incertidumbre del usuario
4. **Consistencia:** Mismo patrón de loading en toda la aplicación
5. **Accesibilidad:** Los estados de carga son visibles y comprensibles
6. **Performance Percibida:** La app se siente más rápida y responsiva

## 🔍 Debugging

### El spinner no se muestra

**Verificar:**
1. Que el `wire:target` coincida exactamente con el nombre del método
2. Que el spinner tenga `wire:loading`
3. Que el `style="display: none;"` esté presente
4. Que el componente spinner exista y esté registrado

### El loading state no desaparece

**Verificar:**
1. Que el método Livewire esté completando correctamente
2. Que no haya errores de JavaScript en la consola
3. Que Livewire esté correctamente inicializado

### Múltiples spinners se muestran

**Solución:**
Usa targets más específicos:

```blade
{{-- En lugar de --}}
wire:target="edit"

{{-- Usa --}}
wire:target="edit({{ $id }})"
```

## 📝 Notas Finales

- Todos los componentes de botones ahora soportan `loadingTarget`
- Los loading states son opcionales pero altamente recomendados
- El sistema es completamente compatible con modo oscuro (dark mode)
- Los spinners son SVG escalables sin pérdida de calidad
- El sistema es compatible con Livewire 3.x

## 🔄 Mantenimiento

Al agregar nuevas funcionalidades:

1. ✅ Identifica operaciones asíncronas
2. ✅ Añade loading states apropiados
3. ✅ Prueba en diferentes velocidades de conexión
4. ✅ Verifica compatibilidad con dark mode
5. ✅ Actualiza esta documentación si es necesario
