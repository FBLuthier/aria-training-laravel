# PHPUnit - Configuración y Uso

## 📋 Información General

**PHPUnit** es el framework de pruebas unitarias utilizado en el proyecto Aria Training. Esta herramienta permite ejecutar pruebas automatizadas de manera consistente y confiable.

## 🛠️ Configuración

### Archivo de Configuración: phpunit.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
    bootstrap="vendor/autoload.php"
    colors="true"
    cacheDirectory=".phpunit.cache"
    backupGlobals="false"
    processIsolation="false"
    failOnRisky="true"
    failOnWarning="true"
>
    <testsuites>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>

    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
    </php>
</phpunit>
```

### Variables de Entorno Específicas para Pruebas

| Variable | Valor en Testing | Propósito |
|----------|------------------|-----------|
| `APP_ENV` | `testing` | Activa modo de pruebas |
| `DB_CONNECTION` | `sqlite` | Base de datos en memoria rápida |
| `CACHE_DRIVER` | `array` | Cache en memoria para velocidad |
| `QUEUE_CONNECTION` | `sync` | Procesamiento sincrónico |
| `SESSION_DRIVER` | `array` | Sesiones en memoria |

## 🚀 Comandos de Ejecución

### Ejecución Básica
```bash
# Ejecutar todas las pruebas
php artisan test

# Equivalente usando PHPUnit directamente
php vendor/bin/phpunit

# Ejecutar con colores y salida detallada
php artisan test --colors=always --verbose
```

### Ejecución Específica
```bash
# Ejecutar pruebas de un archivo específico
php artisan test tests/Feature/Livewire/Admin/GestionEquiposLivewireTest.php

# Ejecutar una prueba específica por nombre
php artisan test --filter="test_administrador_puede_crear_equipos"

# Ejecutar pruebas que contienen texto específico
php artisan test --filter="crear_equipos"

# Ejecutar múltiples archivos
php artisan test tests/Feature/Livewire/Admin/ tests/Feature/Unit/
```

### Opciones Avanzadas
```bash
# Ejecutar con reporte de cobertura (si está configurado)
php artisan test --coverage-html=coverage-report

# Ejecutar en paralelo (más rápido)
php artisan test --parallel

# Ejecutar solo pruebas que fallaron anteriormente
php artisan test --failed

# Ejecutar con salida detallada para debugging
php artisan test --debug
```

## 📊 Interpretación de Resultados

### Salida de PHPUnit

```
PHPUnit 11.5.42 by Sebastian Bergmann and contributors.

..FF.....                                                    9 / 11 (82%)

Time: 00:01.234, Memory: 24.00 MB

There were 2 failures:

1) Tests\Feature\Livewire\Admin\GestionEquiposLivewireTest::test_prueba_fallida
   Failed asserting that false is true.

2) Tests\Feature\Livewire\Admin\GestionEquiposLivewireTest::test_otra_prueba
   Expected status code 200 but received 500.

FAILURES!
Tests: 9 passed, 2 failed.
```

### Indicadores Importantes
- **`.` (punto verde):** Prueba pasó correctamente
- **`F` (F mayúscula):** Prueba falló
- **`E` (E mayúscula):** Error en la prueba (excepción)
- **`R` (R mayúscula):** Prueba marcada como riesgosa
- **`S` (S mayúscula):** Prueba saltada

## 🔧 Características Específicas de Laravel

### Artisan Test Command
Laravel proporciona un wrapper alrededor de PHPUnit con funcionalidades adicionales:

```bash
# Comando básico de Laravel
php artisan test

# Equivalente al comando de PHPUnit
php vendor/bin/phpunit --configuration=phpunit.xml
```

### Integración con Laravel
- **Configuración automática:** Variables de entorno configuradas automáticamente
- **Service providers:** Aplicación Laravel completamente inicializada
- **Base de datos:** Migraciones ejecutadas automáticamente
- **Factories:** Disponibles para generación de datos

## 🚨 Solución de Problemas Comunes

### 1. Base de Datos no Configurada
```bash
# Error típico
PDOException: Connection refused

# Solución: Verificar configuración de testing
php artisan config:cache
php artisan test --debug
```

### 2. Archivos de Prueba no Encontrados
```bash
# Error típico
No tests found in ...

# Solución: Verificar estructura de carpetas
tests/
  Feature/
    Livewire/
      Admin/
        GestionEquiposLivewireTest.php ✅
```

### 3. Errores de Autenticación
```bash
# Error típico
403 Forbidden

# Solución: Verificar configuración de usuario
$admin = User::factory()->create(['tipo_usuario_id' => 1]);
Livewire::actingAs($admin)->test(Componente::class);
```

## 📈 Mejores Prácticas

### Organización de Pruebas
```
tests/
├── Feature/                 # Pruebas de funcionalidades completas
│   └── Livewire/
│       └── Admin/
│           └── GestionEquiposLivewireTest.php
├── Unit/                    # Pruebas unitarias (futuras)
└── CreatesApplication.php   # Trait de Laravel
```

### Convenciones de Nombres
- **Archivos:** `ModuloTest.php` o `ModuloLivewireTest.php`
- **Métodos:** `test_descripcion_clara_en_minusculas_con_guiones_bajos`
- **Variables:** `$admin`, `$equipo`, `$componente` (descriptivos)

---

*Esta documentación describe la configuración y uso de PHPUnit en el proyecto Aria Training.*
