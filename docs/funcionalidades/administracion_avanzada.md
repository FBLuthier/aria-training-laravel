# Funcionalidades Administrativas Avanzadas (v1.7)

Este documento describe las funcionalidades avanzadas de administración implementadas en la Fase 2.

---

## 🎭 Impersonation (Iniciar Sesión Como...)

### Descripción
Permite a los administradores iniciar sesión como cualquier otro usuario del sistema para fines de soporte técnico y diagnóstico de problemas.

### Componentes

#### ImpersonationController
**Ubicación:** `app/Http/Controllers/Admin/ImpersonationController.php`

**Métodos:**
- `impersonate(User $user)`: Guarda el ID del admin en sesión y autentica como el usuario objetivo
- `stop()`: Recupera el admin original y limpia la sesión

**Rutas:**
```php
Route::get('admin/impersonate/{user}', [ImpersonationController::class, 'impersonate'])
    ->name('admin.impersonate');
Route::get('admin/impersonate/stop', [ImpersonationController::class, 'stop'])
    ->name('admin.impersonate.stop');
```

### UI

#### Botón de Impersonación
En la tabla de usuarios (`gestionar-usuarios.blade.php`), se muestra un icono de ojo (SVG) para usuarios que el admin puede impersonar.

**Restricciones:**
- Solo visible para administradores
- No se puede impersonar a uno mismo
- No se puede impersonar a otros administradores (por seguridad)

#### Banner de Impersonación
Cuando un admin está impersonando a otro usuario, se muestra un banner rojo en la parte superior del layout con:
- Mensaje: "Estás navegando como [Nombre del Usuario]"
- Botón: "Volver a mi cuenta"

**Ubicación:** `resources/views/layouts/app.blade.php`

### Flujo de Trabajo
1. Admin ve la lista de usuarios
2. Click en icono de ojo (👁️) del usuario a impersonar
3. Sistema guarda `impersonator_id` en sesión
4. Admin navega la app como el usuario objetivo
5. Banner rojo visible en todo momento
6. Click en "Volver a mi cuenta" para terminar

### Seguridad
- Verificación de rol admin antes de permitir impersonación
- Log de auditoría de acciones durante impersonación mantiene el ID del admin original
- Sesión de impersonación se limpia al cerrar sesión

---

## ⌨️ Command Palette (Ctrl+K)

### Descripción
Buscador global con atajo de teclado para navegación rápida y acceso a funciones sin usar el mouse.

### Componentes

#### CommandPalette
**Ubicación:** `app/Livewire/Components/CommandPalette.php`

**Características:**
- Búsqueda en tiempo real de páginas del sistema
- Búsqueda de usuarios por nombre o email
- Navegación por teclado (flechas arriba/abajo)
- Selección con Enter
- Cierre con Escape

#### Vista
**Ubicación:** `resources/views/livewire/components/command-palette.blade.php`

**Estructura:**
- Modal centrado con fondo semitransparente
- Input de búsqueda con autofocus
- Lista de resultados dividida por categorías (Páginas, Usuarios)
- Indicador visual del elemento seleccionado

### Atajo de Teclado
- **Windows/Linux:** `Ctrl + K`
- **Mac:** `Cmd + K`

### Páginas Indexadas
- Dashboard
- Usuarios
- Rutinas
- Ejercicios
- Equipos
- Grupos Musculares
- Auditoría

### Integración
El componente se incluye globalmente en `layouts/app.blade.php`:
```blade
<livewire:components.command-palette />
```

### Navegación por Teclado
| Tecla | Acción |
|-------|--------|
| `↑` `↓` | Navegar entre resultados |
| `Enter` | Ir al resultado seleccionado |
| `Escape` | Cerrar el palette |

---

## 🖼️ Gestión de Avatares

### Descripción
Permite a los usuarios subir y gestionar su foto de perfil.

### Cambios en Base de Datos

#### Migración
```php
Schema::table('usuarios', function (Blueprint $table) {
    $table->string('profile_photo_path', 2048)->nullable();
});
```

### Modelo User

#### Accessor
**Método:** `getProfilePhotoUrlAttribute()`

```php
public function getProfilePhotoUrlAttribute(): string
{
    if ($this->profile_photo_path) {
        return Storage::url($this->profile_photo_path);
    }
    
    // Fallback a UI Avatars
    return 'https://ui-avatars.com/api/?name=' . urlencode($this->nombreCompleto) . '&color=7F9CF5&background=EBF4FF';
}
```

### Formulario de Usuario

#### UserForm
**Ubicación:** `app/Livewire/Forms/UserForm.php`

**Nueva propiedad:**
```php
public $photo; // TemporaryUploadedFile
```

**Validación:**
```php
'photo' => 'nullable|image|max:1024', // 1MB máximo
```

#### Vista del Formulario
**Ubicación:** `resources/views/livewire/admin/usuarios-form.blade.php`

**Características:**
- Input file para seleccionar imagen
- Preview en tiempo real con Alpine.js
- Muestra avatar actual si existe
- Límite de 1MB

### Storage

**Disco:** `public`
**Directorio:** `avatars/`
**Link simbólico:** Requiere ejecutar `php artisan storage:link`

### Servicios Externos
**UI Avatars:** Genera avatares automáticos basados en iniciales cuando el usuario no tiene foto.
- URL: `https://ui-avatars.com`
- Colores personalizados para mantener consistencia visual

---

## 🔑 Reseteo de Contraseña por Admin

### Descripción
Permite a los administradores restablecer la contraseña de cualquier usuario sin necesidad de acceso a la base de datos.

### Componente Livewire

**Ubicación:** `app/Livewire/Admin/GestionarUsuarios.php`

**Nuevas propiedades:**
```php
public $resettingPasswordId = null;
public $newPassword = '';
```

**Nuevos métodos:**
```php
public function confirmPasswordReset($id)
{
    $this->resettingPasswordId = $id;
    $this->newPassword = '';
}

public function generatePassword()
{
    $this->newPassword = $this->userService->generateSecurePassword(10);
}

public function performPasswordReset()
{
    $this->validate(['newPassword' => 'required|min:8']);
    $user = User::findOrFail($this->resettingPasswordId);
    $this->userService->resetPassword($user, $this->newPassword);
    // Reset state and notify
}
```

### UI

#### Botón de Reseteo
Icono de llave (🔑) SVG en la columna de acciones de cada usuario.

#### Modal de Reseteo
- Input de texto para nueva contraseña
- Botón "Generar" para crear contraseña aleatoria
- Mensaje recordando copiar la contraseña
- Botones Cancelar y Guardar

### Generación de Contraseñas
Usa `Illuminate\Support\Str::password()` para generar contraseñas seguras:
- Longitud configurable (default: 10 caracteres)
- Incluye mayúsculas, minúsculas, números y símbolos

### Seguridad
- Solo visible para usuarios con permiso `update` sobre el usuario
- La contraseña se hashea automáticamente via el cast `hashed` del modelo
- No se guarda la contraseña en texto plano en ningún log

---

## 🏗️ Service Layer (Capa de Servicios)

### Descripción
Refactorización arquitectónica que extrae la lógica de negocio de los componentes Livewire a clases de servicio dedicadas.

### UserService

**Ubicación:** `app/Services/UserService.php`

**Métodos:**
```php
class UserService
{
    // CRUD
    public function create(UserData $data): User;
    public function update(User $user, UserData $data): User;
    public function delete(User $user): bool;
    public function restore(User $user): bool;
    public function forceDelete(User $user): bool;
    
    // Password Management
    public function generateSecurePassword(int $length = 12): string;
    public function resetPassword(User $user, ?string $password = null): string;
    
    // Queries
    public function getVisibleUsers(User $viewer, ?string $search, ?int $role, bool $trash);
}
```

### RutinaService

**Ubicación:** `app/Services/RutinaService.php`

**Métodos:**
```php
class RutinaService
{
    // Estado
    public function toggleActive(Rutina $rutina): bool;
    public function getActiveRutinaForAthlete(User $athlete): ?Rutina;
    
    // Queries
    public function getVisibleRutinas(User $viewer, ?int $athlete, ?string $search, bool $trash);
    public function getAvailableAthletes(User $viewer): Collection;
    
    // CRUD
    public function delete(Rutina $rutina): bool;
    public function restore(Rutina $rutina): bool;
    public function forceDelete(Rutina $rutina): bool;
}
```

### Inyección de Dependencias

Los servicios se inyectan en los componentes Livewire via el método `mount()`:

```php
class GestionarUsuarios extends Component
{
    protected UserService $userService;
    
    public function mount(UserService $userService)
    {
        $this->userService = $userService;
        // ...
    }
    
    public function performDelete()
    {
        $this->userService->delete($user);
    }
}
```

### Beneficios

| Aspecto | Antes | Después |
|---------|-------|---------|
| **Testabilidad** | Difícil (lógica en Livewire) | Fácil (servicios aislados) |
| **Reutilización** | Limitada | Desde cualquier contexto |
| **Mantenimiento** | Disperso | Centralizado |
| **Legibilidad** | Componentes grandes | Componentes delgados |

### Cuándo Usar Servicios

**Usar Service cuando:**
- La lógica involucra múltiples modelos
- Necesitas reutilizar desde diferentes contextos (controller, command, job)
- La operación tiene efectos secundarios complejos
- Quieres facilitar el testing unitario

**Mantener en Componente cuando:**
- Es lógica simple de UI
- Solo afecta al estado del componente
- No necesita reutilización

---

## 📁 Archivos Creados/Modificados

### Nuevos Archivos
- `app/Http/Controllers/Admin/ImpersonationController.php`
- `app/Livewire/Components/CommandPalette.php`
- `resources/views/livewire/components/command-palette.blade.php`
- `app/Services/UserService.php`
- `app/Services/RutinaService.php`
- `resources/views/components/dialog-modal.blade.php`
- `database/migrations/xxxx_add_profile_photo_to_users_table.php`

### Archivos Modificados
- `app/Models/User.php` (accessor avatar)
- `app/Livewire/Admin/GestionarUsuarios.php` (password reset, service injection)
- `app/Livewire/Admin/GestionarRutinas.php` (service injection)
- `app/Livewire/Forms/UserForm.php` (photo upload)
- `resources/views/livewire/admin/gestionar-usuarios.blade.php` (botones, modal)
- `resources/views/livewire/admin/usuarios-form.blade.php` (avatar upload)
- `resources/views/layouts/app.blade.php` (command palette, impersonation banner)
- `routes/web.php` (rutas impersonation)
