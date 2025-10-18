# Guía de Documentación del Código

## 🎯 Objetivo

Este documento establece el estándar de documentación para todo el código del proyecto, garantizando que cada archivo sea educativo y fácil de entender.

---

## 📋 Estándar de Documentación

### **1. Encabezado de Archivo**

```php
<?php

/**
 * =======================================================================
 * TÍTULO DESCRIPTIVO DEL ARCHIVO
 * =======================================================================
 * 
 * Descripción general de lo que hace este archivo.
 * Explicar el propósito y contexto.
 * 
 * FUNCIONALIDADES:
 * - Función 1
 * - Función 2
 * - Función 3
 * 
 * MODO DE USO:
 * ```php
 * // Código de ejemplo
 * $ejemplo = new ClaseEjemplo();
 * ```
 * 
 * BENEFICIOS:
 * - Beneficio 1
 * - Beneficio 2
 * 
 * @package Namespace\Del\Archivo
 * @since Versión
 */
```

---

### **2. Secciones de Código**

Separar lógicamente el código en secciones:

```php
// =======================================================================
//  NOMBRE DE LA SECCIÓN
// =======================================================================

// Código de la sección...
```

**Ejemplos de secciones comunes:**
- `PROPIEDADES ESPECÍFICAS`
- `MÉTODOS ABSTRACTOS`
- `MÉTODOS PÚBLICOS`
- `MÉTODOS PRIVADOS`
- `LIFECYCLE HOOKS`
- `COMPUTED PROPERTIES`
- `HELPERS`

---

### **3. Propiedades**

```php
// =======================================================================
//  PROPIEDADES ESPECÍFICAS
// =======================================================================

/** @var TipoUsuario Tipo de usuario logueado */
protected TipoUsuario $tipoUsuario;

/** @var string Búsqueda principal del componente */
public string $search = '';

/** @var bool Indica si se está mostrando la papelera */
public bool $showingTrash = false;

/** @var array Listeners de eventos específicos */
protected $listeners = [
    'equipoDeleted' => '$refresh',
    'equipoRestored' => '$refresh'
];
```

**Reglas:**
- Usar `@var` para especificar el tipo
- Descripción clara y concisa
- Agrupar propiedades relacionadas

---

### **4. Métodos**

```php
/**
 * Descripción breve de qué hace el método.
 * 
 * Explicación más detallada si es necesario.
 * Incluir ejemplos de uso si el método es complejo.
 * 
 * Ejemplos:
 * - Caso 1
 * - Caso 2
 * 
 * @param TipoParam $parametro Descripción del parámetro
 * @return TipoRetorno Descripción del retorno
 * @throws TipoExcepcion Cuándo se lanza
 */
public function nombreMetodo($parametro): TipoRetorno
{
    // Comentario explicando bloque de código
    $resultado = $this->hacerAlgo($parametro);
    
    // Comentario explicando validación
    if (!$resultado) {
        return null;
    }
    
    // Comentario explicando transformación
    return $this->transformar($resultado);
}
```

**Reglas:**
- Explicar QUÉ hace, no CÓMO (el código ya muestra el cómo)
- Incluir `@param` para cada parámetro
- Incluir `@return` si retorna algo
- Incluir `@throws` si lanza excepciones
- Comentarios inline para lógica compleja

---

### **5. Constantes**

```php
// =======================================================================
//  CONSTANTES
// =======================================================================

/** @var int Número máximo de intentos de login */
private const MAX_LOGIN_ATTEMPTS = 5;

/** @var string Formato de fecha por defecto */
private const DEFAULT_DATE_FORMAT = 'd/m/Y';
```

---

### **6. Traits**

```php
/**
 * =======================================================================
 * TRAIT PARA [FUNCIONALIDAD]
 * =======================================================================
 * 
 * Este trait proporciona [funcionalidad] reutilizable.
 * 
 * USO:
 * ```php
 * class MiClase
 * {
 *     use MiTrait;
 * }
 * ```
 * 
 * MÉTODOS PROPORCIONADOS:
 * - metodo1(): Descripción
 * - metodo2(): Descripción
 */
trait MiTrait
{
    // ...
}
```

---

### **7. Comentarios Inline**

```php
// Para lógica compleja, agregar comentarios explicativos

// Obtener solo items activos (no eliminados)
$items = $query->whereNull('deleted_at')->get();

// Aplicar descuento del 10% si es usuario premium
if ($user->isPremium()) {
    $price = $price * 0.9;
}

// Formatear fecha al formato español
$fecha = \Carbon\Carbon::parse($date)->format('d/m/Y');
```

---

## 📚 Ejemplos por Tipo de Archivo

### **Controllers**

```php
<?php

namespace App\Http\Controllers;

/**
 * Controlador para gestión de equipos.
 * 
 * Maneja todas las operaciones CRUD de equipos de gimnasio.
 * 
 * @package App\Http\Controllers
 */
class EquipoController extends Controller
{
    // =======================================================================
    //  MÉTODOS CRUD
    // =======================================================================
    
    /**
     * Muestra listado de equipos.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ...
    }
}
```

---

### **Models**

```php
<?php

namespace App\Models;

/**
 * Modelo Equipo.
 * 
 * Representa un equipo de gimnasio (mancuernas, barras, etc.).
 * 
 * RELACIONES:
 * - Ninguna
 * 
 * SCOPES:
 * - activos(): Solo equipos no eliminados
 * 
 * @package App\Models
 */
class Equipo extends Model
{
    // =======================================================================
    //  CONFIGURACIÓN DEL MODELO
    // =======================================================================
    
    /** @var array Campos asignables en masa */
    protected $fillable = ['nombre'];
}
```

---

### **Livewire Components**

```php
<?php

namespace App\Livewire\Admin;

/**
 * Componente para gestionar equipos.
 * 
 * Proporciona interfaz completa de CRUD para equipos de gimnasio.
 * 
 * FUNCIONALIDADES:
 * - Crear, editar, eliminar equipos
 * - Búsqueda y filtrado
 * - Exportación a Excel/CSV/PDF
 * - Gestión de papelera
 * 
 * @package App\Livewire\Admin
 */
class GestionarEquipos extends BaseCrudComponent
{
    // =======================================================================
    //  PROPIEDADES ESPECÍFICAS
    // =======================================================================
    
    /** @var EquipoForm Formulario para crear/editar */
    public EquipoForm $form;
}
```

---

### **Blade Views**

```blade
{{-- 
=======================================================================
VISTA: Gestión de Equipos
=======================================================================

Esta vista proporciona la interfaz para gestionar equipos de gimnasio.

COMPONENTES USADOS:
- <x-crud-toolbar>: Barra de búsqueda y acciones
- <x-data-table>: Tabla de datos
- <x-crud-modals>: Modales de confirmación

VARIABLES DISPONIBLES:
- $this->items: Equipos paginados
- $this->search: Término de búsqueda
- $showingTrash: Vista de papelera activa
--}}

<div>
    {{-- Título de la página --}}
    <x-slot name="header">
        <h2>{{ $showingTrash ? 'Papelera' : 'Equipos' }}</h2>
    </x-slot>
    
    {{-- Contenido principal --}}
    <div class="container">
        {{-- Toolbar con búsqueda y botones --}}
        <x-crud-toolbar />
    </div>
</div>
```

---

## ✅ Checklist de Documentación

Antes de considerar un archivo "bien documentado", verificar:

- [ ] Tiene encabezado con descripción general
- [ ] Secciones claramente separadas con comentarios
- [ ] Cada propiedad tiene @var con tipo y descripción
- [ ] Cada método tiene PHPDoc con @param y @return
- [ ] Lógica compleja tiene comentarios inline
- [ ] Incluye ejemplos de uso si es complejo
- [ ] Menciona dependencias o requisitos
- [ ] Explica el "por qué", no solo el "qué"

---

## 🎓 Principios

1. **Educativo**: El código debe enseñar al leerlo
2. **Consistente**: Mismo estilo en todo el proyecto
3. **Conciso**: No explicar lo obvio
4. **Contextual**: Dar contexto del negocio cuando sea relevante
5. **Actualizado**: Mantener docs sincronizadas con código

---

## 📝 Archivos Prioritarios a Documentar

### **Alta Prioridad:**
1. ✅ BaseCrudComponent
2. ✅ BaseModelForm  
3. ✅ BaseQueryBuilder
4. ✅ BaseAdminPolicy
5. ✅ WithExport
6. ✅ BaseCrudTest
7. ✅ ViewHelpers
8. ✅ BaseSeeder
9. ✅ MakeCrudCommand

### **Media Prioridad:**
10. WithCrudOperations
11. WithBulkActions
12. HasFormModal
13. Modelos (Equipo, User, etc.)
14. Policies individuales

### **Baja Prioridad:**
15. Vistas Blade
16. Configuraciones
17. Rutas

---

*Última actualización: 2025-10-17*
*Versión: 1.7 - Estándar de documentación establecido*
