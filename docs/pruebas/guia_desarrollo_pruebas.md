# Guía para Desarrollo de Pruebas - Aria Training

## 🎯 Introducción

Esta guía establece los estándares y procedimientos para desarrollar nuevas pruebas automatizadas en el proyecto Aria Training. Define el patrón establecido y las mejores prácticas para mantener la calidad y consistencia del sistema de pruebas.

## 📋 Patrón Estándar de Desarrollo

### 1. Estructura Básica de una Prueba

```php
<?php

namespace Tests\Feature\Livewire\Admin;

use App\Models\User;
use App\Models\TipoUsuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NuevoModuloTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Configurar datos iniciales
        TipoUsuario::create(['id' => 1, 'rol' => 'Administrador']);
        TipoUsuario::create(['id' => 2, 'rol' => 'Entrenador']);
        TipoUsuario::create(['id' => 3, 'rol' => 'Atleta']);
    }

    public function test_descripcion_clara_y_concisa(): void
    {
        // Arrange: Preparar datos y contexto
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        // Act: Ejecutar la acción a probar
        $componente = Livewire::actingAs($admin)
            ->test(GestionarModulo::class)
            ->call('metodo', $parametro);

        // Assert: Verificar resultados esperados
        $componente->assertOk()
                   ->assertSet('propiedad', 'valorEsperado');
        $this->assertDatabaseHas('tabla', ['campo' => 'valor']);
    }
}
```

### 2. Convenciones de Nomenclatura

#### Nombres de Clases de Prueba
```
✅ GestionEquiposLivewireTest    # Nombre del componente + Livewire + Test
✅ GestionUsuariosTest           # Nombre del módulo + Test
❌ EquipmentManagementTest       # Evitar nombres en inglés
❌ EquipTest                     # Demasiado corto e impreciso
```

#### Nombres de Métodos de Prueba
```
✅ test_administrador_puede_crear_equipos
✅ test_componente_valida_nombre_requerido
✅ test_busqueda_filtra_equipos_correctamente
❌ testCreate                        # Demasiado corto
❌ test_equipment_creation_works    # En inglés
❌ testAdminCanCreateEquipment      # CamelCase inconsistente
```

#### Descripción Clara y Acción-Verbo
```
✅ test_administrador_puede_crear_equipos     # ¿Quién? ¿Qué acción? ¿Qué?
✅ test_componente_valida_nombre_requerido    # ¿Qué? ¿Qué valida? ¿Cómo?
✅ test_busqueda_filtra_equipos_correctamente # ¿Qué? ¿Qué hace? ¿Cómo?
```

## 🏗️ Patrón Arrange-Act-Assert (AAA)

### 1. Arrange (Preparar)
```php
// Crear usuarios necesarios
$admin = User::factory()->create(['tipo_usuario_id' => 1]);

// Crear datos de prueba
$equipo = Equipo::factory()->create(['nombre' => 'Equipo existente']);

// Preparar contexto específico
$datosFormulario = ['nombre' => 'Nuevo equipo'];
```

### 2. Act (Ejecutar)
```php
// Ejecutar la acción que se quiere probar
$resultado = $this->actingAs($admin)
    ->post('/admin/equipos', $datosFormulario);

// O para componentes Livewire
$componente = Livewire::actingAs($admin)
    ->test(GestionarEquipos::class)
    ->call('create')
    ->set('form.nombre', 'Nombre del equipo')
    ->call('save');
```

### 3. Assert (Verificar)
```php
// Verificar resultados en el componente
$componente->assertOk()                    // HTTP 200
           ->assertSet('propiedad', 'valor') // Estado del componente
           ->assertHasNoErrors();            // Sin errores de validación

// Verificar cambios en la base de datos
$this->assertDatabaseHas('equipos', ['nombre' => 'Equipo creado']);
$this->assertDatabaseMissing('equipos', ['nombre' => 'Equipo antiguo']);
```

## 📊 Categorías de Pruebas

### 1. Pruebas de Autorización
```php
public function test_administrador_puede_acceder_al_modulo(): void
{
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    Livewire::actingAs($admin)
            ->test(GestionarModulo::class)
            ->assertOk();  // Debe cargar correctamente
}

public function test_usuario_normal_no_puede_acceder(): void
{
    $atleta = User::factory()->create(['tipo_usuario_id' => 3]);

    Livewire::actingAs($atleta)
            ->test(GestionarModulo::class)
            ->assertForbidden();  // Debe retornar 403
}
```

### 2. Pruebas CRUD
```php
public function test_puede_crear_recurso(): void
{
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    Livewire::actingAs($admin)
            ->test(GestionarModulo::class)
            ->call('create')
            ->set('form.nombre', 'Nuevo recurso')
            ->call('save')
            ->assertHasNoErrors();

    $this->assertDatabaseHas('recursos', ['nombre' => 'Nuevo recurso']);
}
```

### 3. Pruebas de Validación
```php
public function test_valida_campo_requerido(): void
{
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    Livewire::actingAs($admin)
            ->test(GestionarModulo::class)
            ->call('create')
            ->set('form.nombre', '')  // Campo vacío
            ->call('save')
            ->assertHasErrors(['form.nombre']);  // Debe tener errores

    $this->assertDatabaseMissing('recursos', ['nombre' => '']);
}
```

### 4. Pruebas de Características Avanzadas
```php
public function test_busqueda_filtra_correctamente(): void
{
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    // Crear datos de prueba
    Recurso::factory()->create(['nombre' => 'Mancuernas 10kg']);
    Recurso::factory()->create(['nombre' => 'Banca olímpica']);

    Livewire::actingAs($admin)
            ->test(GestionarModulo::class)
            ->set('search', 'Mancuernas')  // Buscar término
            ->assertSet('search', 'Mancuernas');  // Verificar establecido
}
```

## 🛠️ Uso de Factories

### Crear Datos de Prueba Consistentes
```php
// Crear usuario administrador
$admin = User::factory()->create(['tipo_usuario_id' => 1]);

// Crear recurso con datos específicos
$equipo = Equipo::factory()->create([
    'nombre' => 'Equipo específico',
    'descripcion' => 'Descripción personalizada'
]);

// Crear múltiples recursos para pruebas de lista
$equipos = Equipo::factory()->count(5)->create();
```

### Personalizar Factories para Casos Específicos
```php
// Factory personalizado para casos extremos
$equipoEspecial = Equipo::factory()->create([
    'nombre' => 'Equipo con caracteres especiales @#$%'
]);
```

## 🔍 Estrategias de Debugging

### Cuando una Prueba Falla

1. **Verificar datos de entrada**
```php
// Agregar logging temporal para debugging
dump($component->get('form'));  // Ver estado del formulario
dump($component->get('errors')); // Ver errores del componente
```

2. **Verificar estado de la base de datos**
```php
// Verificar si los datos se guardaron correctamente
$this->assertDatabaseHas('equipos', ['nombre' => 'Equipo creado']);
```

3. **Verificar permisos y autorización**
```php
// Verificar que el usuario tiene permisos correctos
$admin = User::factory()->create(['tipo_usuario_id' => 1]);
$this->assertEquals(1, $admin->tipo_usuario_id);
```

## 📈 Mejores Prácticas

### Principios a Seguir
1. **Una prueba = Una funcionalidad:** Cada método prueba una sola cosa
2. **Nombres descriptivos:** El nombre debe explicar qué se prueba
3. **Datos independientes:** Cada prueba debe ser independiente
4. **Limpieza automática:** Usar RefreshDatabase para datos limpios
5. **Velocidad:** Mantener pruebas rápidas (< 1 segundo ideal)

### Evitar Errores Comunes
- ❌ **Pruebas demasiado complejas** (más de 20 líneas)
- ❌ **Dependencias entre pruebas** (cada prueba debe ser independiente)
- ❌ **Uso de datos reales** (siempre usar datos de prueba)
- ❌ **Falta de assertions claros** (siempre verificar resultados)

## 🚀 Checklist para Nuevas Pruebas

### Antes de Implementar
- [ ] ✅ **Caso de uso identificado** (¿Qué funcionalidad se prueba?)
- [ ] ✅ **Nombre descriptivo definido** (¿El nombre explica claramente lo que hace?)
- [ ] ✅ **Datos de prueba preparados** (¿Qué datos necesito crear?)
- [ ] ✅ **Resultado esperado definido** (¿Qué debo verificar?)

### Durante la Implementación
- [ ] ✅ **Patrón AAA seguido** (Arrange, Act, Assert)
- [ ] ✅ **Factories utilizados** (para datos consistentes)
- [ ] ✅ **Assertions apropiados** (¿Verifico todos los aspectos necesarios?)
- [ ] ✅ **Código limpio** (¿Es fácil de leer y entender?)

### Después de Implementar
- [ ] ✅ **Prueba ejecutada** (¿Todas las pruebas pasan?)
- [ ] ✅ **Tiempo medido** (¿Se ejecuta en tiempo razonable?)
- [ ] ✅ **Documentación actualizada** (¿Se agregó a la documentación?)
- [ ] ✅ **Código committed** (¿Se incluyó en el repositorio?)

## 📞 Soporte y Mantenimiento

### Para Preguntas o Problemas
- **Documentación técnica:** Esta guía y archivos relacionados
- **Código fuente:** `tests/Feature/Livewire/Admin/`
- **Equipo responsable:** Equipo de desarrollo Aria Training

### Mantenimiento de Pruebas
- **Revisión periódica:** Verificar que las pruebas siguen siendo relevantes
- **Actualización continua:** Modificar pruebas cuando cambie la funcionalidad
- **Limpieza:** Eliminar pruebas obsoletas o redundantes

---

*Esta guía establece los estándares para el desarrollo y mantenimiento del sistema de pruebas de Aria Training. Última actualización: Octubre 2025*
