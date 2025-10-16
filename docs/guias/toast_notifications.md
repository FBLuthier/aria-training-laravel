# Sistema de Notificaciones Toast

## 📋 Descripción General

El sistema de notificaciones toast proporciona feedback visual elegante y no intrusivo al usuario después de completar acciones. Los toasts aparecen en la esquina superior derecha, se auto-descartan después de unos segundos y son completamente accesibles.

## 🎨 Características

- ✅ **4 tipos de notificación:** success, error, warning, info
- ✅ **Auto-dismiss configurable:** Con barra de progreso visual
- ✅ **Apilamiento inteligente:** Múltiples toasts se apilan elegantemente
- ✅ **Animaciones suaves:** Transiciones con Alpine.js
- ✅ **Compatible con dark mode:** Totalmente responsivo
- ✅ **Accesible:** ARIA labels y roles apropiados
- ✅ **Cierre manual:** Botón X para cerrar antes del auto-dismiss
- ✅ **Integración total:** Funciona con Livewire y JavaScript puro

## 🚀 Uso Básico

### Desde Componentes Livewire (PHP)

La forma más común de usar toasts es desde componentes Livewire:

```php
// Notificación de éxito
$this->dispatch('notify', message: 'Operación completada exitosamente.', type: 'success');

// Notificación de error
$this->dispatch('notify', message: 'Ocurrió un error al procesar la solicitud.', type: 'error');

// Notificación de advertencia
$this->dispatch('notify', message: 'Esta acción es irreversible.', type: 'warning');

// Notificación informativa
$this->dispatch('notify', message: 'El proceso puede tardar unos minutos.', type: 'info');

// Con duración personalizada (en milisegundos)
$this->dispatch('notify', message: 'Este mensaje permanecerá 10 segundos.', type: 'success', duration: 10000);

// Sin auto-dismiss (requiere cierre manual)
$this->dispatch('notify', message: 'Este mensaje no se cerrará automáticamente.', type: 'info', duration: 0);
```

### Desde JavaScript

Usa los helpers globales disponibles:

```javascript
// Método principal
notify('Mensaje de éxito', 'success', 5000);

// Atajos específicos por tipo
notifySuccess('Operación exitosa');
notifyError('Error al guardar');
notifyWarning('Advertencia importante');
notifyInfo('Información relevante');

// Con duración personalizada
notifySuccess('Mensaje largo', 10000); // 10 segundos

// Sin auto-dismiss
notifyError('Error crítico', 0); // Permanece hasta cerrar manualmente
```

### Desde Alpine.js (Inline)

```blade
<button 
    @click="$dispatch('notify', { 
        message: 'Botón presionado', 
        type: 'success' 
    })"
>
    Probar Toast
</button>
```

### Desde Session Flash (Redirecciones)

Para mostrar toasts después de redirecciones:

```php
// En el controlador
return redirect()->route('home')->with([
    'toast' => true,
    'message' => 'Inicio de sesión exitoso',
    'type' => 'success'
]);
```

```blade
<!-- En la vista Blade -->
<x-toast-trigger />
```

## 📦 Componentes Disponibles

### 1. Toast Container (`<x-toast-container>`)

Componente principal que maneja todos los toasts. **Ya está incluido en `layouts/app.blade.php`**, no necesitas agregarlo en tus vistas.

```blade
<!-- Ya está en layouts/app.blade.php -->
<x-toast-container />
```

### 2. Toast Trigger (`<x-toast-trigger>`)

Helper para mostrar toasts desde session flash:

```blade
<!-- Uso básico -->
<x-toast-trigger />

<!-- Con keys personalizadas -->
<x-toast-trigger 
    key="notification"
    messageKey="msg"
    typeKey="status"
/>
```

## 🎯 Tipos de Notificación

### Success (Éxito)
- **Color:** Verde
- **Icono:** Check/Palomita
- **Uso:** Operaciones completadas exitosamente
- **Ejemplos:** "Guardado correctamente", "Datos actualizados"

```php
$this->dispatch('notify', message: 'Equipo creado exitosamente.', type: 'success');
```

### Error (Error)
- **Color:** Rojo
- **Icono:** X
- **Uso:** Errores, fallos en operaciones
- **Ejemplos:** "Error al guardar", "No se pudo conectar"

```php
$this->dispatch('notify', message: 'No se pudo eliminar el registro.', type: 'error');
```

### Warning (Advertencia)
- **Color:** Amarillo/Naranja
- **Icono:** Triángulo de advertencia
- **Uso:** Advertencias, acciones que requieren atención
- **Ejemplos:** "Acción irreversible", "Datos faltantes"

```php
$this->dispatch('notify', message: 'Esta acción no se puede deshacer.', type: 'warning');
```

### Info (Información)
- **Color:** Azul
- **Icono:** i en círculo
- **Uso:** Información general, actualizaciones
- **Ejemplos:** "Proceso en curso", "Nueva funcionalidad disponible"

```php
$this->dispatch('notify', message: 'La exportación está en proceso.', type: 'info');
```

## 💡 Ejemplos Prácticos

### Ejemplo 1: CRUD Completo

```php
class GestionarEquipos extends Component
{
    public function save()
    {
        try {
            $this->form->save();
            
            $message = $this->form->equipo->wasRecentlyCreated 
                ? 'Equipo creado exitosamente.' 
                : 'Equipo actualizado exitosamente.';
                
            $this->dispatch('notify', message: $message, type: 'success');
            $this->closeModal();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error al guardar el equipo.', type: 'error');
        }
    }
    
    public function delete($id)
    {
        $this->deletingId = $id;
        $this->dispatch('notify', message: '¿Estás seguro de eliminar este equipo?', type: 'warning', duration: 0);
    }
    
    public function performDelete()
    {
        $equipo = Equipo::find($this->deletingId);
        $equipo->delete();
        
        $this->dispatch('notify', message: 'Equipo eliminado correctamente.', type: 'success');
        $this->deletingId = null;
    }
}
```

### Ejemplo 2: Validación

```php
public function submit()
{
    $this->validate();
    
    // Si pasa la validación
    $this->dispatch('notify', message: 'Formulario enviado correctamente.', type: 'success');
}

// En caso de error de validación (automático con Livewire)
protected $messages = [
    'email.required' => 'El correo es obligatorio.',
];

// Mostrar toast de error en validación
public function updated($propertyName)
{
    $this->validateOnly($propertyName);
    
    if ($this->getErrorBag()->has($propertyName)) {
        $this->dispatch('notify', 
            message: 'Hay errores en el formulario.', 
            type: 'error'
        );
    }
}
```

### Ejemplo 3: Procesos Largos

```php
public function exportData()
{
    // Notificar inicio
    $this->dispatch('notify', 
        message: 'Iniciando exportación de datos...', 
        type: 'info'
    );
    
    try {
        // Proceso de exportación
        $this->processExport();
        
        // Notificar éxito
        $this->dispatch('notify', 
            message: 'Datos exportados exitosamente. Descargando archivo...', 
            type: 'success',
            duration: 7000
        );
        
    } catch (\Exception $e) {
        $this->dispatch('notify', 
            message: 'Error al exportar datos: ' . $e->getMessage(), 
            type: 'error',
            duration: 10000
        );
    }
}
```

### Ejemplo 4: Acciones en Lote

```php
public function deleteSelected()
{
    $count = count($this->selectedItems);
    
    if ($count === 0) {
        $this->dispatch('notify', 
            message: 'No hay elementos seleccionados.', 
            type: 'warning'
        );
        return;
    }
    
    Equipo::whereIn('id', $this->selectedItems)->delete();
    
    $this->dispatch('notify', 
        message: "{$count} equipos eliminados correctamente.", 
        type: 'success'
    );
    
    $this->clearSelection();
}
```

## 🎨 Personalización

### Modificar Duración por Defecto

En el componente `toast-container.blade.php`, cambia el valor por defecto:

```javascript
@notify.window="addToast($event.detail.message, $event.detail.type || 'success', $event.detail.duration || 5000)"
//                                                                                                    ^^^^^ Cambiar aquí
```

### Modificar Posición

En `toast-container.blade.php`, cambia las clases de posición:

```blade
<!-- Actualmente: Esquina superior derecha -->
class="fixed top-4 right-4 z-50 space-y-3"

<!-- Esquina superior izquierda -->
class="fixed top-4 left-4 z-50 space-y-3"

<!-- Centro superior -->
class="fixed top-4 left-1/2 -translate-x-1/2 z-50 space-y-3"

<!-- Esquina inferior derecha -->
class="fixed bottom-4 right-4 z-50 space-y-3"
```

### Cambiar Colores

Modifica la función `getToastConfig` en `toast-container.blade.php`:

```javascript
getToastConfig(type) {
    const configs = {
        success: {
            bgColor: 'bg-green-500',  // ← Cambiar color aquí
            icon: `...`,
            title: 'Éxito'
        },
        // ... otros tipos
    };
    return configs[type] || configs.info;
}
```

### Deshabilitar Auto-dismiss Globalmente

```javascript
// En app.js, modificar el helper
window.notify = function(message, type = 'success', duration = 0) { // ← duration = 0
    window.dispatchEvent(new CustomEvent('notify', {
        detail: { message, type, duration }
    }));
};
```

### Agregar Sonidos

```javascript
// En toast-container.blade.php, en la función addToast
addToast(message, type = 'success', duration = 5000) {
    // ... código existente ...
    
    // Reproducir sonido según el tipo
    if (type === 'error') {
        new Audio('/sounds/error.mp3').play();
    } else if (type === 'success') {
        new Audio('/sounds/success.mp3').play();
    }
}
```

## 📱 Responsividad

Los toasts son completamente responsivos:

```blade
<!-- Desktop: 420px de ancho -->
style="max-width: 420px;"

<!-- Mobile: Se ajusta automáticamente -->
class="w-full max-w-sm"
```

Para personalizar el ancho en mobile:

```blade
<!-- En toast-container.blade.php -->
<div class="w-full max-w-sm sm:max-w-md lg:max-w-lg">
    <!-- contenido -->
</div>
```

## 🔧 Integración con Formularios

### Con Validación de Laravel

```php
public function submit()
{
    $validated = $this->validate([
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
    ]);
    
    try {
        User::create($validated);
        
        $this->dispatch('notify', 
            message: 'Usuario creado exitosamente.', 
            type: 'success'
        );
        
        $this->reset();
        
    } catch (\Exception $e) {
        $this->dispatch('notify', 
            message: 'Error al crear usuario: ' . $e->getMessage(), 
            type: 'error'
        );
    }
}
```

### Con Form Request

```php
public function store(StoreEquipoRequest $request)
{
    $equipo = Equipo::create($request->validated());
    
    return redirect()
        ->route('equipos.index')
        ->with([
            'toast' => true,
            'message' => 'Equipo creado exitosamente.',
            'type' => 'success'
        ]);
}
```

## 🎯 Mejores Prácticas

### 1. Mensajes Claros y Concisos

```php
// ✅ BIEN: Claro y específico
$this->dispatch('notify', message: 'Equipo "Mancuernas 10kg" creado exitosamente.', type: 'success');

// ❌ MAL: Muy genérico
$this->dispatch('notify', message: 'Operación exitosa.', type: 'success');
```

### 2. Tipo Apropiado

```php
// ✅ BIEN: Tipo correcto para cada situación
$this->dispatch('notify', message: 'Datos guardados.', type: 'success');
$this->dispatch('notify', message: 'Error de conexión.', type: 'error');
$this->dispatch('notify', message: 'Acción irreversible.', type: 'warning');
$this->dispatch('notify', message: 'Proceso en curso.', type: 'info');

// ❌ MAL: Tipo incorrecto
$this->dispatch('notify', message: 'Error crítico.', type: 'success');
```

### 3. Duración Apropiada

```php
// ✅ BIEN: Duración según importancia
$this->dispatch('notify', message: 'Guardado.', type: 'success', duration: 3000); // Mensaje corto
$this->dispatch('notify', message: 'Error al conectar con el servidor. Intenta nuevamente.', type: 'error', duration: 7000); // Mensaje importante
$this->dispatch('notify', message: 'ACCIÓN IRREVERSIBLE: ¿Estás seguro?', type: 'warning', duration: 0); // Requiere acción

// ❌ MAL: Duración muy corta para mensaje importante
$this->dispatch('notify', message: 'Error crítico que requiere atención inmediata.', type: 'error', duration: 1000);
```

### 4. No Saturar al Usuario

```php
// ✅ BIEN: Un toast por acción
$this->dispatch('notify', message: '5 equipos eliminados correctamente.', type: 'success');

// ❌ MAL: Múltiples toasts para la misma acción
foreach ($equipos as $equipo) {
    $this->dispatch('notify', message: "Equipo {$equipo->nombre} eliminado.", type: 'success');
}
```

### 5. Feedback Inmediato

```php
// ✅ BIEN: Toast después de completar la acción
public function delete($id)
{
    $equipo = Equipo::find($id);
    $equipo->delete();
    
    $this->dispatch('notify', message: 'Equipo eliminado.', type: 'success');
}

// ❌ MAL: Toast antes de la acción
public function delete($id)
{
    $this->dispatch('notify', message: 'Equipo eliminado.', type: 'success'); // ← Muy pronto
    
    $equipo = Equipo::find($id);
    $equipo->delete(); // ← Esto puede fallar
}
```

## 🐛 Debugging

### Toast No Aparece

**Verificar:**
1. Que `<x-toast-container />` esté en `layouts/app.blade.php`
2. Que Alpine.js esté cargado y funcionando
3. Que el formato del dispatch sea correcto
4. Consola del navegador para errores de JavaScript

```javascript
// Test manual en consola del navegador
notify('Mensaje de prueba', 'success');
```

### Toast Aparece Pero No Se Cierra

**Verificar:**
1. Que el `duration` no sea 0 (que significa sin auto-dismiss)
2. Que no haya errores de JavaScript interrumpiendo el temporizador
3. Revisar la consola para warnings de Alpine.js

### Múltiples Toasts Superpuestos

Esto es normal y esperado. Para limitar:

```javascript
// En toast-container.blade.php, modificar addToast
addToast(message, type = 'success', duration = 5000) {
    // Limitar a máximo 3 toasts
    if (this.toasts.length >= 3) {
        this.removeToast(this.toasts[0].id);
    }
    
    // ... resto del código
}
```

## 📊 Implementación Actual

### GestionarEquipos
- ✅ Crear equipo
- ✅ Actualizar equipo
- ✅ Eliminar equipo (soft delete)
- ✅ Restaurar equipo
- ✅ Eliminar permanentemente
- ✅ Acciones en lote (eliminar, restaurar, force delete)

### GestionarAuditoria
- ✅ Limpiar filtros
- ⏳ Exportar datos (pendiente implementación completa)

## 📚 Referencias

- [Alpine.js Events](https://alpinejs.dev/essentials/events)
- [Livewire Events](https://livewire.laravel.com/docs/events)
- [Toast UI/UX Best Practices](https://uxdesign.cc/toast-notifications-best-practices-and-examples-9b4c8e9c8c8)

## 🔄 Próximas Mejoras

- [ ] Agregar animación de entrada más llamativa
- [ ] Soporte para acciones en el toast (ej: "Deshacer")
- [ ] Agrupación de toasts similares
- [ ] Historial de notificaciones
- [ ] Configuración de usuario para preferencias de toast
