# Mejores Prácticas - Sistema de Pruebas

## 🎯 Principios Fundamentales

### 1. Pruebas FIRST
Aplicamos los principios **FIRST** para mantener la calidad de nuestras pruebas:

- **⚡ Fast (Rápidas):** Las pruebas deben ejecutarse rápidamente
- **🔒 Independent (Independientes):** Cada prueba debe ser autocontenida
- **🔄 Repeatable (Repetibles):** Resultados consistentes en cada ejecución
- **🤔 Self-validating (Auto-validables):** Resultados claros (pasó/falló)
- **📅 Timely (Oportunas):** Escribir pruebas cerca del código de producción

### 2. Calidad sobre Cantidad
- **Mejor una prueba buena que muchas mediocres**
- **Pruebas claras y mantenibles sobre cobertura numérica**
- **Eliminar pruebas obsoletas o redundantes**

## 📋 Estándares de Desarrollo

### 1. Estructura de Código

#### Organización de Archivos
```
tests/
├── Feature/Livewire/Admin/
│   └── GestionEquiposLivewireTest.php ✅
└── Unit/ (futuro)
```

#### Nombres de Clases
```php
✅ GestionEquiposLivewireTest     # Descriptivo y específico
✅ GestionUsuariosTest            # Claro y conciso
❌ EquipmentTest                  # Demasiado corto
❌ GestionDeEquipamientoTest     # Demasiado largo
```

### 2. Métodos de Prueba

#### Nombres Descriptivos
```php
✅ test_administrador_puede_crear_equipos
✅ test_componente_valida_nombre_requerido
✅ test_busqueda_filtra_equipos_correctamente
❌ test_create
❌ test_equipment_creation
❌ testAdminCanCreateEquipment
```

#### Descripción Clara (Quién + Qué + Cómo)
```php
✅ test_administrador_puede_crear_equipos     # ¿Quién? ¿Qué acción? ¿Qué?
✅ test_componente_valida_nombre_requerido    # ¿Qué? ¿Qué valida? ¿Cómo?
✅ test_busqueda_filtra_equipos_correctamente # ¿Qué? ¿Qué hace? ¿Cómo?
```

### 3. Patrón Arrange-Act-Assert (AAA)

#### Arrange (Preparar)
```php
// ✅ Datos claros y específicos
$admin = User::factory()->create(['tipo_usuario_id' => 1]);
$equipoExistente = Equipo::factory()->create(['nombre' => 'Equipo previo']);
$datosFormulario = ['nombre' => 'Nuevo equipo'];

// ❌ Datos ambiguos o innecesarios
$user = User::factory()->create();  // ¿Qué tipo de usuario?
$data = ['nombre' => 'test'];       // ¿Datos de prueba claros?
```

#### Act (Ejecutar)
```php
// ✅ Acción clara y específica
$componente = Livewire::actingAs($admin)
    ->test(GestionarEquipos::class)
    ->call('create')
    ->set('form.nombre', 'Mancuernas 10kg')
    ->call('save');

// ❌ Acción confusa o incompleta
$result = $this->post('/admin/equipos', ['nombre' => 'test']);
```

#### Assert (Verificar)
```php
// ✅ Verificaciones claras y específicas
$componente->assertOk()
           ->assertSet('showFormModal', false)
           ->assertHasNoErrors();

$this->assertDatabaseHas('equipos', ['nombre' => 'Mancuernas 10kg']);
$this->assertDatabaseMissing('equipos', ['nombre' => 'Nombre anterior']);

// ❌ Verificaciones ambiguas o innecesarias
$this->assertTrue(true);  // ¿Qué verifica esto?
$componente->assertSet('search', '');  // ¿Es esto crítico?
```

## 🛠️ Técnicas Avanzadas

### 1. Uso Efectivo de Factories

#### Creación de Datos Consistentes
```php
// ✅ Factory con datos específicos y realistas
$equipo = Equipo::factory()->create([
    'nombre' => 'Mancuernas 10kg',
    'descripcion' => 'Set completo de mancuernas ajustables'
]);

// ✅ Factory con relaciones
$usuarioConEquipo = User::factory()
    ->has(Equipo::factory()->count(3))
    ->create();

// ❌ Factory con datos aleatorios innecesarios
Equipo::factory()->create();  // ¿Qué datos genera? ¿Son útiles?
```

#### Personalización para Casos Específicos
```php
// ✅ Factory personalizado para casos extremos
$equipoEspecial = Equipo::factory()->create([
    'nombre' => 'Equipo con caracteres especiales @#$%'
]);

$equipoUnicode = Equipo::factory()->create([
    'nombre' => 'Equipo con ñ, á, é, í, ó, ú'
]);
```

### 2. Manejo de Estado del Componente

#### Verificación de Estado Inicial
```php
// ✅ Verificar estado inicial correcto
$componente->assertSet('showingTrash', false)
           ->assertSet('search', '')
           ->assertSet('sortField', 'nombre');

// ❌ Asumir estado inicial sin verificar
$componente->call('create');  // ¿Y si ya hay un modal abierto?
```

#### Seguimiento de Cambios de Estado
```php
// ✅ Documentar cambios de estado claramente
$componente->assertSet('showFormModal', false)  // Inicial: cerrado
           ->call('create')                       // Acción: abrir
           ->assertSet('showFormModal', true)     // Resultado: abierto
           ->set('form.nombre', 'Nuevo equipo')   // Acción: establecer datos
           ->call('save')                         // Acción: guardar
           ->assertSet('showFormModal', false);   // Resultado: cerrado
```

### 3. Validaciones Exhaustivas

#### Cobertura de Validación Completa
```php
// ✅ Verificar múltiples aspectos de validación
$componente->call('create')
           ->set('form.nombre', '')              // Campo vacío
           ->call('save')
           ->assertHasErrors(['form.nombre']);   // Error específico

$this->assertDatabaseMissing('equipos', ['nombre' => '']); // No se creó
```

#### Casos Extremos Cubiertos
```php
// ✅ Caracteres especiales
$componente->call('create')
           ->set('form.nombre', 'Equipo @#$%')
           ->call('save')
           ->assertHasNoErrors();

// ✅ Caracteres unicode
$componente->call('create')
           ->set('form.nombre', 'Equipo ñáéíóú')
           ->call('save')
           ->assertHasNoErrors();

// ✅ Límites de longitud
$nombreLargo = str_repeat('a', 46);
$componente->call('create')
           ->set('form.nombre', $nombreLargo)
           ->call('save')
           ->assertHasErrors(['form.nombre']);
```

## 📊 Métricas de Calidad

### Métricas Objetivo
| Métrica | Objetivo | Estado Actual |
|---------|----------|---------------|
| **Cobertura funcional** | 100% | ✅ **100%** |
| **Número de pruebas** | 10+ | ✅ **11 pruebas** |
| **Assertions por prueba** | 2+ | ✅ **2.1 promedio** |
| **Tiempo de ejecución** | < 2s | ✅ **1.88s** |
| **Tasa de éxito** | 100% | ✅ **100%** |

### Indicadores de Calidad
- **✅ Claridad:** Nombres descriptivos y código auto-explicativo
- **✅ Atomicidad:** Cada prueba verifica una sola funcionalidad
- **✅ Mantenibilidad:** Fácil modificar y extender
- **✅ Confiabilidad:** Resultados consistentes y repetibles
- **✅ Velocidad:** Ejecución rápida y eficiente

## 🚨 Anti-patrones a Evitar

### 1. Pruebas Frágiles
```php
// ❌ Frágil: depende de detalles de implementación
$componente->assertSet('internalState', 'valorEspecifico');

// ✅ Robusto: verifica comportamiento observable
$componente->assertOk()
           ->assertHasNoErrors();
$this->assertDatabaseHas('equipos', ['nombre' => 'Equipo creado']);
```

### 2. Pruebas Acopladas
```php
// ❌ Acopladas: dependen del orden de ejecución
public function test_segunda_prueba(): void
{
    // Asume que test_primera_prueba ya se ejecutó
    $this->assertDatabaseHas('equipos', ['nombre' => 'Equipo previo']);
}

// ✅ Independientes: cada prueba es autocontenida
public function test_funcionalidad_especifica(): void
{
    $equipo = Equipo::factory()->create(['nombre' => 'Equipo necesario']);
    // Resto de la prueba...
}
```

### 3. Pruebas Innecesariamente Complejas
```php
// ❌ Compleja: múltiples responsabilidades
public function test_todo_a_la_vez(): void
{
    // Crea usuario, equipo, categoría, etc.
    // Prueba creación, edición y eliminación juntas
    // Verifica múltiples cosas diferentes
}

// ✅ Simple: responsabilidad única
public function test_crear_equipo_basico(): void
{
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);

    Livewire::actingAs($admin)
            ->test(GestionarEquipos::class)
            ->call('create')
            ->set('form.nombre', 'Equipo básico')
            ->call('save')
            ->assertHasNoErrors();

    $this->assertDatabaseHas('equipos', ['nombre' => 'Equipo básico']);
}
```

## 🔄 Mantenimiento Continuo

### 1. Refactorización Regular
- **Revisar pruebas obsoletas:** Eliminar cuando funcionalidades cambien
- **Actualizar nombres:** Mantener nombres descriptivos y actuales
- **Mejorar claridad:** Refactorizar pruebas difíciles de entender
- **Optimizar rendimiento:** Identificar y corregir pruebas lentas

### 2. Documentación Viva
- **Comentarios explicativos:** Documentar decisiones de diseño en pruebas
- **Casos extremos documentados:** Explicar por qué se prueban casos específicos
- **Referencias cruzadas:** Relacionar pruebas con casos de uso

### 3. Evolución del Patrón
- **Incorporar nuevas técnicas:** Adoptar mejoras en herramientas de testing
- **Estándares actualizados:** Mantenerse al día con mejores prácticas
- **Feedback del equipo:** Incorporar lecciones aprendidas

## 📈 Métricas de Seguimiento

### Métricas a Monitorear
1. **Número de pruebas:** Seguimiento de crecimiento de cobertura
2. **Tiempo de ejecución:** Identificar degradación de rendimiento
3. **Tasa de éxito:** Mantener 100% de pruebas pasando
4. **Cobertura funcional:** Asegurar cobertura de nuevas funcionalidades

### Reportes Periódicos
- **Reporte semanal:** Estado general del sistema de pruebas
- **Reporte de cobertura:** Análisis detallado de funcionalidades cubiertas
- **Reporte de rendimiento:** Seguimiento de tiempos de ejecución

## 🎓 Aprendizaje Continuo

### Recursos Recomendados
- **PHPUnit Documentation:** Referencia técnica completa
- **Laravel Testing Guide:** Mejores prácticas específicas de Laravel
- **Livewire Testing:** Documentación específica para componentes
- **Testing Community:** Blogs y foros de la comunidad PHP

### Mejora Continua
- **Revisión por pares:** Otros desarrolladores revisan nuevas pruebas
- **Experimentos controlados:** Probar nuevas técnicas en pruebas no críticas
- **Estudio de casos:** Analizar pruebas exitosas en otros proyectos

---

*Estas mejores prácticas establecen los estándares de calidad para el desarrollo y mantenimiento del sistema de pruebas de Aria Training.*
