# Toast Notifications - Referencia Rápida

## 🚀 Uso Rápido

### Desde Livewire (PHP)
```php
// Éxito
$this->dispatch('notify', message: 'Operación exitosa', type: 'success');

// Error
$this->dispatch('notify', message: 'Ocurrió un error', type: 'error');

// Advertencia
$this->dispatch('notify', message: 'Atención requerida', type: 'warning');

// Información
$this->dispatch('notify', message: 'Información importante', type: 'info');

// Con duración personalizada (ms)
$this->dispatch('notify', message: 'Mensaje largo', type: 'success', duration: 10000);

// Sin auto-dismiss
$this->dispatch('notify', message: 'Requiere acción', type: 'warning', duration: 0);
```

### Desde JavaScript
```javascript
// Método principal
notify('Mensaje', 'tipo', duracion);

// Atajos
notifySuccess('Operación exitosa');
notifyError('Error crítico');
notifyWarning('Cuidado');
notifyInfo('FYI');

// Con duración personalizada
notifySuccess('Mensaje', 10000); // 10 segundos
```

### Desde Alpine.js (Inline)
```blade
<button @click="$dispatch('notify', { message: 'Hola', type: 'success' })">
    Mostrar Toast
</button>
```

### Desde Session Flash
```php
// Controlador
return redirect()->back()->with([
    'toast' => true,
    'message' => 'Acción completada',
    'type' => 'success'
]);
```

```blade
<!-- Vista -->
<x-toast-trigger />
```

## 📋 Tipos Disponibles

| Tipo | Color | Uso | Ejemplo |
|------|-------|-----|---------|
| `success` | Verde | Operaciones exitosas | "Guardado correctamente" |
| `error` | Rojo | Errores | "No se pudo eliminar" |
| `warning` | Amarillo | Advertencias | "Acción irreversible" |
| `info` | Azul | Información | "Proceso en curso" |

## ⚙️ Parámetros

| Parámetro | Tipo | Default | Descripción |
|-----------|------|---------|-------------|
| `message` | string | - | Mensaje a mostrar (requerido) |
| `type` | string | 'success' | Tipo de notificación |
| `duration` | number | 5000 | Duración en ms (0 = sin auto-dismiss) |

## 💡 Ejemplos Comunes

### CRUD
```php
// Crear
$this->dispatch('notify', message: 'Registro creado', type: 'success');

// Actualizar
$this->dispatch('notify', message: 'Registro actualizado', type: 'success');

// Eliminar
$this->dispatch('notify', message: 'Registro eliminado', type: 'success');

// Error al guardar
$this->dispatch('notify', message: 'Error al guardar', type: 'error');
```

### Validación
```php
// Validación exitosa
$this->dispatch('notify', message: 'Formulario válido', type: 'success');

// Errores de validación
$this->dispatch('notify', message: 'Revisa los campos', type: 'warning');
```

### Acciones en Lote
```php
$count = count($this->selected);
$this->dispatch('notify', message: "{$count} registros procesados", type: 'success');
```

### Procesos Largos
```php
// Inicio
$this->dispatch('notify', message: 'Iniciando proceso...', type: 'info');

// Fin
$this->dispatch('notify', message: 'Proceso completado', type: 'success', duration: 7000);
```

## 🎨 Personalización Rápida

### Cambiar Posición
```blade
<!-- En toast-container.blade.php -->
<!-- Superior derecha (default) -->
class="fixed top-4 right-4"

<!-- Superior izquierda -->
class="fixed top-4 left-4"

<!-- Inferior derecha -->
class="fixed bottom-4 right-4"

<!-- Centro superior -->
class="fixed top-4 left-1/2 -translate-x-1/2"
```

### Cambiar Duración Default
```javascript
// En toast-container.blade.php
@notify.window="addToast(..., duration || 5000)" // ← Cambiar 5000
```

### Limitar Cantidad
```javascript
// En toast-container.blade.php, función addToast
if (this.toasts.length >= 3) {
    this.removeToast(this.toasts[0].id);
}
```

## ✅ Checklist de Implementación

- [x] `<x-toast-container />` en `layouts/app.blade.php`
- [x] Helpers de JavaScript disponibles globalmente
- [ ] Toasts en todas las acciones CRUD
- [ ] Toasts en validaciones
- [ ] Toasts en acciones en lote
- [ ] Toasts en procesos de exportación
- [ ] Mensajes claros y específicos

## 🐛 Troubleshooting

### Toast no aparece
```javascript
// Test en consola del navegador
notify('Test', 'success');
```

- ¿Alpine.js está cargado?
- ¿`<x-toast-container />` está en el layout?
- ¿Hay errores en consola?

### Toast no se cierra
- Verificar que `duration` no sea 0
- Revisar errores de JavaScript
- Verificar que Alpine.js funciona correctamente

## 📱 Responsive

Los toasts son completamente responsive automáticamente:
- **Desktop:** 420px de ancho
- **Mobile:** Se ajusta al 100% con padding

## 🎯 Best Practices

### ✅ Hacer
- Mensajes claros y concisos
- Tipo apropiado según la situación
- Duración adecuada al mensaje
- Un toast por acción

### ❌ Evitar
- Mensajes muy genéricos
- Tipo incorrecto para la situación
- Duración muy corta para mensajes importantes
- Múltiples toasts para la misma acción

## 📚 Ver También

- [Documentación completa](./toast_notifications.md)
- [Sistema de Loading States](./loading_states.md)
- [Componentes de la Aplicación](../components/)
