# Guía de Mantenimiento - Sistema de Pruebas (Gestión de Equipos)

## 🎯 Propósito

Esta guía proporciona procedimientos claros para mantener, actualizar y extender el sistema de pruebas del módulo de gestión de equipos. Establece estándares para asegurar que las pruebas sigan siendo efectivas y confiables a medida que evoluciona el código.

## 📋 Procedimientos de Mantenimiento

### 1. Actualización de Pruebas Existentes

#### Cuando Modificar una Prueba
- **Cambios en la lógica de negocio:** Cuando el componente Livewire cambie su comportamiento
- **Nuevas validaciones:** Cuando se agreguen restricciones adicionales
- **Cambios en la interfaz:** Cuando se modifiquen elementos del formulario o tabla
- **Actualizaciones de autorización:** Cuando cambien las políticas de acceso

#### Procedimiento de Actualización
```php
// 1. Identificar pruebas afectadas
// 2. Ejecutar pruebas actuales para confirmar estado
// 3. Modificar código de prueba según cambios
// 4. Ejecutar pruebas para verificar funcionamiento
// 5. Actualizar documentación si es necesario
// 6. Commit con descripción clara de cambios
```

### 2. Adición de Nuevas Pruebas

#### Proceso para Nuevas Funcionalidades
1. **Identificar nueva funcionalidad** a probar
2. **Crear prueba siguiendo patrón establecido**
3. **Ejecutar prueba para verificar funcionamiento**
4. **Agregar a documentación correspondiente**
5. **Commit con descripción clara**

#### Plantilla para Nueva Prueba
```php
public function test_nueva_funcionalidad(): void
{
    // Arrange: Preparar datos y contexto
    $admin = User::factory()->create(['tipo_usuario_id' => 1]);
    $datosPrueba = Equipo::factory()->create();

    // Act: Ejecutar la funcionalidad
    $componente = Livewire::actingAs($admin)
        ->test(GestionarEquipos::class)
        ->call('nuevoMetodo', $datosPrueba->id);

    // Assert: Verificar resultados
    $componente->assertOk()
               ->assertSet('propiedadNueva', 'valorEsperado');
    $this->assertDatabaseHas('equipos', ['campo_nuevo' => 'valor']);
}
```

### 3. Eliminación de Pruebas Obsoletas

#### Criterios para Eliminar Pruebas
- **Funcionalidad eliminada:** Cuando se remueve código del componente
- **Pruebas redundantes:** Cuando múltiples pruebas verifican lo mismo
- **Pruebas incorrectas:** Cuando la prueba no verifica comportamiento real

#### Procedimiento de Eliminación
1. **Identificar pruebas candidatas** para eliminación
2. **Ejecutar todas las pruebas** para asegurar que la eliminación no rompe cobertura
3. **Eliminar código de prueba**
4. **Actualizar documentación** relacionada
5. **Commit con explicación clara**

## 🔧 Mantenimiento Técnico

### 1. Mantenimiento de Base de Datos de Pruebas

#### Limpieza Automática
- **RefreshDatabase:** Se ejecuta automáticamente antes de cada prueba
- **Rollback automático:** Los cambios se revierten después de cada prueba
- **Estado limpio:** Cada prueba inicia con base de datos limpia

#### Datos de Prueba Consistentes
- **Factories utilizadas:** Asegurar que factories generan datos válidos
- **Relaciones correctas:** Verificar que relaciones entre modelos funcionan
- **Datos realistas:** Usar datos que representen escenarios reales

### 2. Mantenimiento de Componentes Livewire

#### Verificación de Componentes
- **Propiedades públicas:** Asegurar que propiedades usadas en pruebas existen
- **Métodos públicos:** Verificar que métodos llamados en pruebas están disponibles
- **Estado inicial:** Confirmar que estado inicial del componente es correcto

#### Sincronización con Código Real
- **Nombres de propiedades:** Deben coincidir exactamente con el componente
- **Valores por defecto:** Verificar que valores iniciales son correctos
- **Comportamiento esperado:** Las pruebas deben reflejar comportamiento real

### 3. Mantenimiento de Configuración

#### Archivo de Configuración
```php
// phpunit.xml - Verificar configuración
<?xml version="1.0" encoding="UTF-8"?>
<phpunit>
    <testsuites>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

#### Variables de Entorno
- **APP_ENV=testing:** Modo de pruebas activado
- **DB_CONNECTION:** Base de datos de pruebas configurada
- **CACHE_DRIVER:** Driver de cache apropiado para pruebas

## 📊 Monitoreo y Reportes

### 1. Ejecución Regular de Pruebas

#### Frecuencia Recomendada
- **Antes de cada commit:** Todas las pruebas deben pasar
- **Integración continua:** Configurar pipeline automático
- **Revisión de código:** Pruebas ejecutadas por pares

#### Comandos Útiles
```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar pruebas específicas
php artisan test tests/Feature/Livewire/Admin/GestionEquiposLivewireTest.php

# Ejecutar con reporte detallado
php artisan test --verbose

# Ejecutar solo pruebas que contienen texto específico
php artisan test --filter="test_administrador_puede_crear_equipos"
```

### 2. Análisis de Métricas

#### Métricas a Monitorear
- **Tiempo de ejecución total**
- **Número de pruebas ejecutadas**
- **Número de assertions verificados**
- **Porcentaje de éxito**

#### Reportes Generados
- **PHPUnit output:** Información detallada de ejecución
- **Coverage reports:** Análisis de cobertura (cuando esté disponible)
- **Performance metrics:** Seguimiento de tiempos de ejecución

### 3. Alertas y Notificaciones

#### Configuración de Alertas
- **Pruebas fallidas:** Notificación inmediata al equipo
- **Degradación de rendimiento:** Alerta si tiempo excede límites
- **Cobertura reducida:** Notificación si cobertura baja de 80%

## 🚨 Solución de Problemas Comunes

### 1. Pruebas Fallidas

#### Problemas Comunes
- **Datos de prueba incorrectos**
- **Cambios en el componente no reflejados en pruebas**
- **Problemas de timing en pruebas asincrónicas**
- **Dependencias entre pruebas**

#### Procedimiento de Debugging
1. **Ejecutar prueba individualmente** para aislar el problema
2. **Revisar logs de Laravel** para errores adicionales
3. **Verificar datos de prueba** creados por factories
4. **Comparar con comportamiento esperado** del componente

### 2. Pruebas Lentas

#### Optimizaciones Posibles
- **Reducir datos de prueba:** Usar solo datos necesarios
- **Optimizar consultas:** Verificar que consultas son eficientes
- **Paralelización:** Ejecutar pruebas en paralelo cuando sea posible

#### Límites de Rendimiento
- **Tiempo máximo aceptable:** 5 segundos para todas las pruebas
- **Tiempo ideal:** Menos de 2 segundos para pruebas rápidas
- **Límite por prueba:** 1 segundo máximo por prueba individual

### 3. Pruebas Inconsistentes

#### Causas Comunes
- **Orden de ejecución:** Pruebas dependen del orden
- **Datos residuales:** Información de pruebas anteriores afecta
- **Estado global:** Variables estáticas o caché afectan resultados

#### Soluciones
- **Pruebas independientes:** Cada prueba debe ser autocontenida
- **Limpieza adecuada:** Usar RefreshDatabase correctamente
- **Estado inicial consistente:** Verificar estado inicial en setUp()

## 📚 Documentación Relacionada

### Archivos de Referencia
- `estrategia_pruebas.md` - Estrategia general y metodología
- `guia_desarrollo_pruebas.md` - Guía detallada para desarrollo
- `cobertura_actual.md` - Reporte actual de cobertura

### Recursos Externos
- [PHPUnit Documentation](https://phpunit.readthedocs.io/)
- [Laravel Testing Guide](https://laravel.com/docs/testing)
- [Livewire Testing](https://laravel-livewire.com/docs/testing)

## 🔄 Ciclo de Vida de las Pruebas

### Fases del Mantenimiento
1. **Creación inicial:** Nueva prueba desarrollada y verificada
2. **Estabilización:** Prueba ejecutándose consistentemente
3. **Mantenimiento:** Actualizaciones según cambios en código
4. **Optimización:** Mejoras de rendimiento y mantenibilidad
5. **Retiro:** Eliminación cuando deja de ser necesaria

### Responsabilidades del Equipo
- **Desarrolladores:** Mantener pruebas relacionadas con su código
- **Equipo de QA:** Revisar cobertura y calidad general
- **Tech Lead:** Aprobar cambios significativos en estrategia

## 📞 Soporte y Contacto

### Para Problemas o Preguntas
- **Documentación técnica:** Esta guía y documentos relacionados
- **Código fuente:** `tests/Feature/Livewire/Admin/GestionEquiposLivewireTest.php`
- **Equipo responsable:** Equipo de desarrollo Aria Training

### Procedimiento para Solicitar Cambios
1. **Crear issue** en el sistema de seguimiento
2. **Describir problema** o mejora solicitada
3. **Proponer solución** si es posible
4. **Revisión por equipo técnico**
5. **Implementación y pruebas**

---

*Esta guía establece los procedimientos para mantener la calidad y efectividad del sistema de pruebas a lo largo del tiempo.*
