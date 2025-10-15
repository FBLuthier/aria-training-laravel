# Livewire Testing - Guía Técnica

## 🎯 Introducción

**Livewire Testing** es una herramienta específica de Laravel Livewire que permite probar componentes interactivos de manera efectiva. Esta guía documenta el patrón establecido en el proyecto Aria Training para testing de componentes Livewire.

## 🏗️ Arquitectura de Livewire Testing

### ¿Qué es Livewire Testing?
- **Herramienta integrada:** Parte del paquete `livewire/livewire`
- **Testing de componentes:** Permite probar componentes como si fueran controladores
- **Interacciones reales:** Simula interacciones reales del usuario
- **Estado del componente:** Acceso directo al estado interno del componente

### Sintaxis Básica
```php
// Crear instancia del componente para testing
$component = Livewire::actingAs($usuario)
    ->test(GestionarEquipos::class);

// Interactuar con el componente
$component->call('metodoDelComponente', $parametro)
          ->set('propiedad', 'nuevoValor')
          ->assertSet('propiedad', 'nuevoValor')
          ->assertHasNoErrors();
```

## 📋 Patrón Establecido en Aria Training

### 1. Configuración Inicial
```php
public function test_funcionalidad_especifica(): void
{
    // 1. Preparar usuario autenticado
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    // 2. Crear datos de prueba necesarios
    $equipo = Equipo::factory()->create(['nombre' => 'Datos existentes']);

    // 3. Crear instancia del componente
    $component = Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\GestionarEquipos::class);
}
```

### 2. Interacciones Comunes

#### Crear Recursos
```php
$component->call('create')                    // Abre modal de creación
          ->set('form.nombre', 'Nuevo equipo') // Establece datos del formulario
          ->call('save')                       // Ejecuta guardado
          ->assertHasNoErrors();               // Verifica sin errores

$this->assertDatabaseHas('equipos', ['nombre' => 'Nuevo equipo']);
```

#### Editar Recursos
```php
$component->call('edit', $equipo->id)         // Abre modal de edición
          ->assertSet('showFormModal', true)  // Verifica modal abierto
          ->set('form.nombre', 'Nombre editado') // Cambia datos
          ->call('save')                       // Guarda cambios
          ->assertHasNoErrors();

$this->assertDatabaseHas('equipos', ['nombre' => 'Nombre editado']);
```

#### Eliminar Recursos
```php
$component->call('delete', $equipo->id)       // Inicia eliminación
          ->call('performDelete')              // Confirma eliminación
          ->assertHasNoErrors();

$this->assertSoftDeleted('equipos', ['id' => $equipo->id]);
```

### 3. Características Avanzadas

#### Búsqueda y Filtrado
```php
// Crear datos de prueba
Equipo::factory()->create(['nombre' => 'Mancuernas 10kg']);
Equipo::factory()->create(['nombre' => 'Banca olímpica']);

$component->set('search', 'Mancuernas')        // Establece búsqueda
          ->assertSet('search', 'Mancuernas'); // Verifica búsqueda establecida
```

#### Ordenamiento
```php
$component->call('sortBy', 'nombre')          // Ordena por campo
          ->assertSet('sortField', 'nombre')   // Verifica campo establecido
          ->assertSet('sortDirection', 'asc'); // Verifica dirección (si aplica)
```

#### Estados del Componente
```php
$component->assertSet('showingTrash', false)  // Verifica estado inicial
          ->assertSet('search', '')            // Campo vacío por defecto
          ->assertSet('sortField', 'nombre')   // Campo orden por defecto
```

## 🔧 Métodos y Propiedades Comunes

### Métodos Públicos de Componente
| Método | Parámetros | Descripción |
|--------|------------|-------------|
| `create()` | Ninguno | Abre modal de creación |
| `edit($id)` | ID del recurso | Abre modal de edición con datos cargados |
| `save()` | Ninguno | Guarda formulario (crear o editar) |
| `delete($id)` | ID del recurso | Inicia proceso de eliminación |
| `performDelete()` | Ninguno | Confirma y ejecuta eliminación |
| `sortBy($campo)` | Nombre del campo | Establece ordenamiento |
| `toggleTrash()` | Ninguno | Muestra/oculta elementos eliminados |

### Propiedades Públicas del Componente
| Propiedad | Tipo | Descripción |
|-----------|------|-------------|
| `equipos` | Collection | Lista de equipos mostrados |
| `search` | String | Término de búsqueda actual |
| `sortField` | String | Campo por el cual se ordena |
| `sortDirection` | String | Dirección del orden ('asc'/'desc') |
| `showingTrash` | Boolean | Si muestra papelera de reciclaje |
| `selectedItems` | Array | IDs de elementos seleccionados |
| `showFormModal` | Boolean | Si el modal de formulario está abierto |

## 🚨 Assertions Específicos de Livewire

### Assertions para Estado del Componente
```php
// Verificar propiedades públicas
$component->assertSet('propiedad', 'valorEsperado')
          ->assertSet('showingTrash', false)
          ->assertSet('search', '');

// Verificar que no hay errores de validación
$component->assertHasNoErrors()
          ->assertHasNoErrors(['form.nombre']);

// Verificar errores específicos
$component->assertHasErrors(['form.nombre'])
          ->assertHasErrors(['form.nombre' => 'El campo nombre es requerido']);
```

### Assertions para Comportamiento HTTP
```php
// Verificar respuestas HTTP estándar
$component->assertOk();              // HTTP 200
$component->assertForbidden();       // HTTP 403
$component->assertRedirect();        // Redirección
$component->assertRedirect('/login'); // Redirección específica
```

### Assertions para Base de Datos
```php
// Verificar cambios en base de datos
$this->assertDatabaseHas('equipos', ['nombre' => 'Equipo creado']);
$this->assertDatabaseMissing('equipos', ['nombre' => 'Equipo eliminado']);
$this->assertSoftDeleted('equipos', ['id' => $equipo->id]);
```

## 📊 Ejemplos Prácticos por Categoría

### 1. Pruebas de Autorización
```php
public function test_solo_administradores_pueden_acceder(): void
{
    $atleta = User::factory()->create(['tipo_usuario_id' => 3]);

    Livewire::actingAs($atleta)
            ->test(GestionarEquipos::class)
            ->assertForbidden();  // Debe retornar 403
}
```

### 2. Pruebas CRUD
```php
public function test_puede_crear_equipos(): void
{
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    Livewire::actingAs($admin)
            ->test(GestionarEquipos::class)
            ->call('create')
            ->set('form.nombre', 'Nuevo equipo')
            ->call('save')
            ->assertHasNoErrors();

    $this->assertDatabaseHas('equipos', ['nombre' => 'Nuevo equipo']);
}
```

### 3. Pruebas de Validación
```php
public function test_valida_nombres_duplicados(): void
{
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    // Crear equipo inicial
    Equipo::factory()->create(['nombre' => 'Equipo existente']);

    // Intentar crear duplicado
    Livewire::actingAs($admin)
            ->test(GestionarEquipos::class)
            ->call('create')
            ->set('form.nombre', 'Equipo existente')
            ->call('save')
            ->assertHasErrors(['form.nombre']);
}
```

### 4. Pruebas de Características UI
```php
public function test_busqueda_funciona_correctamente(): void
{
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    // Crear datos de prueba
    Equipo::factory()->create(['nombre' => 'Mancuernas 10kg']);
    Equipo::factory()->create(['nombre' => 'Banca olímpica']);

    Livewire::actingAs($admin)
            ->test(GestionarEquipos::class)
            ->set('search', 'Mancuernas')
            ->assertSet('search', 'Mancuernas');
}
```

## 🔧 Configuración Avanzada

### Variables de Entorno Específicas
```php
// En .env.testing o phpunit.xml
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
SESSION_DRIVER=array
```

### Configuración de Componente para Testing
```php
// En el componente Livewire
class GestionarEquipos extends Component
{
    public $search = '';           // Búsqueda
    public $sortField = 'nombre';  // Campo de ordenamiento
    public $sortDirection = 'asc'; // Dirección de ordenamiento
    public $showingTrash = false;  // Estado de papelera
    public $selectedItems = [];    // Selección múltiple

    // Constructor
    public function mount(): void
    {
        $this->sortField = 'nombre';  // Estado inicial
    }
}
```

## 📈 Mejores Prácticas Específicas

### 1. Pruebas Atómicas
- **Una funcionalidad por prueba:** Cada método prueba una sola cosa
- **Nombre descriptivo:** El nombre debe explicar claramente qué se prueba
- **Setup mínimo:** Crear solo los datos necesarios para esa prueba

### 2. Manejo de Estado
- **Estado inicial consistente:** Siempre verificar estado inicial del componente
- **Cambios de estado claros:** Documentar cómo cambia el estado durante la prueba
- **Limpieza automática:** RefreshDatabase se encarga de limpiar entre pruebas

### 3. Debugging de Pruebas
```php
// Para debugging temporal
$component->set('search', 'Mancuernas');
dump($component->get('search'));  // Ver valor actual
dump($component->get('equipos')); // Ver datos cargados
```

## 🚨 Solución de Problemas Comunes

### 1. Componente no Encontrado
```php
// Error: Class "App\Livewire\Admin\GestionEquipos" not found

// Solución: Verificar namespace correcto
use App\Livewire\Admin\GestionarEquipos;
```

### 2. Propiedad no Existe
```php
// Error: Property [propiedadIncorrecta] not found on component

// Solución: Verificar nombre exacto de propiedad
$component->assertSet('propiedadCorrecta', 'valor');
```

### 3. Método no Público
```php
// Error: Method [metodoPrivado] is not public

// Solución: Usar solo métodos públicos del componente
$component->call('metodoPublico', $parametro);
```

## 📚 Recursos Adicionales

### Documentación Oficial
- [Livewire Testing Documentation](https://laravel-livewire.com/docs/testing)
- [Laravel Testing Guide](https://laravel.com/docs/testing)
- [PHPUnit Assertions](https://phpunit.readthedocs.io/en/9.5/assertions.html)

### Ejemplos en el Proyecto
- **Archivo principal:** `tests/Feature/Livewire/Admin/GestionEquiposLivewireTest.php`
- **Patrón establecido:** Sigue estructura AAA (Arrange-Act-Assert)
- **Cobertura completa:** 11 pruebas implementadas y funcionando

---

*Esta guía documenta el patrón específico de Livewire Testing establecido en el proyecto Aria Training.*
