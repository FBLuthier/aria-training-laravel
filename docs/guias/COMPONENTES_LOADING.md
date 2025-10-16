# Componentes de Loading - Referencia Rápida

## 🎯 Uso Rápido

### Spinner Simple
```blade
<x-spinner size="sm" color="gray" wire:loading wire:target="action" />
```

### Botón con Loading
```blade
<x-primary-button wire:click="save" loadingTarget="save">
    Guardar
</x-primary-button>
```

### Input con Spinner
```blade
<div class="relative w-full">
    <input wire:model.live="search" />
    <div class="absolute right-3 top-1/2 -translate-y-1/2">
        <x-spinner size="sm" wire:loading wire:target="search" />
    </div>
</div>
```

### Loading State para Tabla
```blade
<x-loading-state 
    target="search,filter,sort" 
    message="Cargando datos..."
/>

<div wire:loading.remove wire:target="search,filter,sort">
    <!-- Contenido -->
</div>
```

### Loading Overlay
```blade
<x-loading-overlay 
    target="exportData" 
    message="Exportando..."
/>
```

## 📋 Props Disponibles

### Spinner
| Prop | Valores | Default |
|------|---------|---------|
| `size` | xs, sm, md, lg, xl | md |
| `color` | current, white, gray, primary, red | current |

### Loading State
| Prop | Tipo | Default |
|------|------|---------|
| `target` | string | null |
| `message` | string | "Cargando..." |
| `inline` | boolean | false |

### Loading Overlay
| Prop | Tipo | Default |
|------|------|---------|
| `target` | string | null |
| `message` | string | "Cargando..." |

### Botones (Primary, Secondary, Danger)
| Prop | Tipo | Default |
|------|------|---------|
| `loadingTarget` | string | null |

## 🎨 Tamaños de Spinner

```blade
<x-spinner size="xs" /> <!-- 12px -->
<x-spinner size="sm" /> <!-- 16px -->
<x-spinner size="md" /> <!-- 20px (default) -->
<x-spinner size="lg" /> <!-- 24px -->
<x-spinner size="xl" /> <!-- 32px -->
```

## 🌈 Colores de Spinner

```blade
<x-spinner color="current" /> <!-- Color actual del texto -->
<x-spinner color="white" />   <!-- Blanco -->
<x-spinner color="gray" />    <!-- Gris (adaptable a dark mode) -->
<x-spinner color="primary" /> <!-- Indigo/Primary -->
<x-spinner color="red" />     <!-- Rojo -->
```

## 💡 Ejemplos Comunes

### Botón de Modal con Loading
```blade
<x-danger-button 
    wire:click="performDelete" 
    loadingTarget="performDelete"
>
    Eliminar
</x-danger-button>
```

### Link de Acción con Spinner
```blade
<button 
    wire:click="restore({{ $id }})" 
    class="inline-flex items-center gap-1"
>
    <x-spinner 
        size="xs" 
        wire:loading 
        wire:target="restore({{ $id }})"
        style="display: none;" 
    />
    <span>Restaurar</span>
</button>
```

### Botón Personalizado con Loading
```blade
<button
    wire:click="export"
    wire:loading.attr="disabled"
    wire:target="export"
    class="flex items-center gap-2 disabled:opacity-50"
>
    <x-spinner 
        size="sm" 
        color="white"
        wire:loading 
        wire:target="export"
        style="display: none;"
    />
    <span wire:loading.remove wire:target="export">Exportar</span>
    <span wire:loading wire:target="export" style="display: none;">
        Exportando...
    </span>
</button>
```

### Tabla Completa con Loading
```blade
<!-- Loading state mientras carga -->
<x-loading-state 
    target="search,sort,gotoPage" 
    message="Cargando registros..."
    class="my-4"
/>

<!-- Tabla (se oculta mientras carga) -->
<div wire:loading.remove wire:target="search,sort,gotoPage">
    <table>
        <!-- contenido -->
    </table>
</div>
```

## ⚡ Tips y Trucos

### Target con Parámetros
```blade
<!-- Para acciones con parámetros específicos -->
wire:target="edit({{ $item->id }})"
wire:target="delete({{ $item->id }})"
```

### Múltiples Targets
```blade
<!-- Separar con comas -->
wire:target="search,filter,sort"
wire:target="create,update,delete"
```

### Deshabilitar Durante Carga
```blade
<button
    wire:click="action"
    wire:loading.attr="disabled"
    wire:target="action"
>
    Acción
</button>
```

### Mostrar/Ocultar Condicionalmente
```blade
<!-- Mostrar solo durante la carga -->
<div wire:loading wire:target="action">
    Cargando...
</div>

<!-- Ocultar durante la carga -->
<div wire:loading.remove wire:target="action">
    Contenido
</div>

<!-- Mostrar con display flex -->
<div wire:loading.flex wire:target="action">
    Cargando...
</div>
```

## 🎯 Patrones Recomendados

### Patrón 1: Input de Búsqueda
```blade
<div class="relative">
    <input wire:model.live.debounce.300ms="search" />
    <div class="absolute right-3 top-1/2 -translate-y-1/2">
        <x-spinner size="sm" color="gray" wire:loading wire:target="search" />
    </div>
</div>
```

### Patrón 2: Botón de Acción Principal
```blade
<x-primary-button wire:click="save" loadingTarget="save">
    Guardar Cambios
</x-primary-button>
```

### Patrón 3: Operación Destructiva
```blade
<x-danger-button 
    wire:click="delete" 
    loadingTarget="delete"
>
    Eliminar Permanentemente
</x-danger-button>

<x-loading-overlay 
    target="delete" 
    message="Eliminando registro..."
/>
```

### Patrón 4: Lista/Tabla Dinámica
```blade
<x-loading-state target="loadData" message="Cargando datos..." />

<div wire:loading.remove wire:target="loadData">
    @foreach($items as $item)
        <!-- items -->
    @endforeach
</div>
```

## 🔧 Integración con Livewire

### En el Componente Livewire (PHP)
```php
class MiComponente extends Component
{
    public function action()
    {
        // El loading state se activa automáticamente
        sleep(2); // Simula operación lenta
        
        // Al terminar, el loading state se desactiva automáticamente
        return redirect()->route('success');
    }
}
```

### En la Vista (Blade)
```blade
<button wire:click="action">
    <x-spinner size="sm" wire:loading wire:target="action" />
    <span wire:loading.remove wire:target="action">Ejecutar</span>
    <span wire:loading wire:target="action">Ejecutando...</span>
</button>
```

## ✅ Checklist de Implementación

Al agregar una nueva funcionalidad, verifica:

- [ ] Identificadas todas las operaciones asíncronas
- [ ] Añadido spinner o loading state apropiado
- [ ] Target especificado correctamente
- [ ] Botones se deshabilitan durante la carga
- [ ] Mensaje de loading es descriptivo
- [ ] Probado en modo claro y oscuro
- [ ] Probado con conexión lenta

## 📚 Ver También

- [Documentación completa de Loading States](./loading_states.md)
- [Componentes de Botones](../components/)
- [Documentación de Livewire](https://livewire.laravel.com)
