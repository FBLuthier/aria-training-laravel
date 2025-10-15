# Procedimientos Operativos - Aria Training

## 🛠️ Mantenimiento y Operaciones

### Información General
**Versión del sistema:** 1.3
**Fecha de documentación:** Octubre 2025
**Responsable de operaciones:** Equipo de desarrollo Aria Training

**Última actualización:** Implementación del Sistema de Exportación de Auditoría con soporte para CSV, XLSX y PDF nativos.

## 📋 Procedimientos de Mantenimiento

### 1. Backup del Sistema de Pruebas

#### Backup Manual
```bash
# 1. Ejecutar todas las pruebas para verificar estado
php artisan test

# 2. Crear respaldo de la estructura de pruebas
cp -r tests/ tests_backup_$(date +%Y%m%d_%H%M%S)/

# 3. Crear respaldo de la documentación
cp -r docs/pruebas/ docs_backup_$(date +%Y%m%d_%H%M%S)/

# 4. Verificar integridad del respaldo
ls -la tests_backup_*/ tests/
ls -la docs_backup_*/ docs/pruebas/
```

#### Restauración de Backup
```bash
# 1. Detener servicios si es necesario
# sudo systemctl stop nginx php-fpm

# 2. Restaurar estructura de pruebas
rm -rf tests/
cp -r tests_backup_*/ tests/

# 3. Restaurar documentación
rm -rf docs/pruebas/
cp -r docs_backup_*/pruebas/ docs/

# 4. Verificar permisos
chmod -R 755 tests/ docs/

# 5. Ejecutar pruebas para verificar integridad
php artisan test
```

### 2. Actualización del Sistema de Pruebas

#### Procedimiento de Actualización Segura
1. **Crear rama de trabajo**
   ```bash
   git checkout -b feature/actualizar-pruebas
   ```

2. **Ejecutar pruebas actuales**
   ```bash
   php artisan test  # Verificar que todo funciona
   ```

3. **Realizar cambios necesarios**
   - Modificar pruebas según cambios en código
   - Actualizar documentación relacionada
   - Verificar funcionamiento

4. **Ejecutar pruebas después de cambios**
   ```bash
   php artisan test  # Verificar que cambios no rompen nada
   ```

5. **Commit con descripción clara**
   ```bash
   git add .
   git commit -m "test: actualizar pruebas para nueva funcionalidad

   why: cambios en componente requieren actualización de pruebas

   what:
   - Modificar test_administrador_puede_crear_equipos para nueva validación
   - Actualizar documentación de pruebas afectadas
   - Agregar casos extremos para nueva funcionalidad

   impact:
   - Pruebas sincronizadas con código actual
   - Cobertura mantenida en funcionalidades modificadas
   - Documentación técnica actualizada"
   ```

### 3. Monitoreo Continuo del Sistema

#### Métricas a Monitorear
| Métrica | Herramienta | Frecuencia | Umbral |
|---------|-------------|------------|--------|
| **Tiempo de pruebas** | PHPUnit | Cada ejecución | < 2.0s |
| **Cobertura de código** | PHPUnit Coverage | Semanal | > 90% |
| **Estado de pruebas** | GitHub Actions | Cada commit | 100% éxito |
| **Uso de recursos** | Server monitoring | Diario | Memoria < 50% |

#### Comandos de Monitoreo
```bash
# Estado actual del sistema de pruebas
php artisan test --verbose

# Métricas detalladas de cobertura
php artisan test --coverage-html=coverage-report

# Estado de la base de datos de pruebas
php artisan tinker
>>> DB::select('SHOW TABLE STATUS LIKE "equipos"');

# Estado de archivos de pruebas
find tests/ -name "*.php" -exec wc -l {} + | tail -1
```

## 🚨 Procedimientos de Emergencia

### 1. Pruebas Fallando en Producción

#### Diagnóstico Rápido
```bash
# 1. Verificar logs de Laravel
tail -f storage/logs/laravel.log

# 2. Ejecutar pruebas en modo debug
php artisan test --debug --verbose

# 3. Verificar configuración de base de datos
php artisan config:cache
php artisan test
```

#### Solución de Problemas Comunes
| Problema | Causa Probable | Solución |
|----------|----------------|----------|
| **Error 403** | Políticas de autorización | Verificar configuración de usuario |
| **Datos no encontrados** | Seeders no ejecutados | `php artisan db:seed` |
| **Tiempo de ejecución excesivo** | Base de datos lenta | Verificar índices y consultas |
| **Memoria insuficiente** | Pruebas muy grandes | Optimizar datos de prueba |

### 2. Corrupción de Base de Datos de Pruebas

#### Procedimiento de Recuperación
1. **Detener ejecución de pruebas**
   ```bash
   # Cancelar cualquier ejecución en curso
   pkill -f "phpunit"
   ```

2. **Limpiar base de datos**
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Verificar integridad**
   ```bash
   php artisan test tests/Feature/Livewire/Admin/GestionEquiposLivewireTest.php::GestionEquiposLivewireTest::test_componente_se_carga_para_administradores
   ```

4. **Restaurar desde backup si necesario**
   ```bash
   # Procedimiento detallado en sección de backup
   ```

## 📊 Reportes y Documentación

### 1. Reportes de Estado del Sistema

#### Reporte Diario de Pruebas
```bash
# Generar reporte básico
php artisan test --log-junit=reports/junit.xml

# Generar reporte HTML detallado
php artisan test --coverage-html=reports/coverage

# Verificar archivos generados
ls -la reports/
```

#### Reporte de Cobertura Completo
```bash
# Generar reporte detallado de cobertura
php artisan test --coverage --min=90

# El reporte mostrará:
# - Porcentaje de cobertura por archivo
# - Líneas cubiertas vs no cubiertas
# - Métricas de calidad del código de pruebas
```

### 2. Documentación de Incidentes

#### Formato para Reportar Problemas
```
**Fecha:** [AAAA-MM-DD HH:MM:SS]
**Versión del sistema:** [Número de versión]
**Ambiente:** [Desarrollo/Producción]
**Descripción del problema:**
[Descripción clara y detallada]

**Pasos para reproducir:**
1. [Paso 1]
2. [Paso 2]
3. [Paso N]

**Resultado esperado:**
[Comportamiento correcto]

**Resultado obtenido:**
[Comportamiento incorrecto observado]

**Archivos afectados:**
- [Lista de archivos relacionados]

**Posible solución:**
[Análisis y propuesta de solución]
```

## 🔧 Configuración de Entorno

### Variables de Entorno Críticas
| Variable | Valor en Desarrollo | Valor en Producción | Propósito |
|----------|-------------------|-------------------|-----------|
| `APP_ENV` | `testing` | `production` | Modo de aplicación |
| `DB_CONNECTION` | `sqlite` | `mysql` | Tipo de base de datos |
| `CACHE_DRIVER` | `array` | `redis` | Sistema de caché |
| `LOG_LEVEL` | `debug` | `error` | Nivel de logging |

### Configuración de Base de Datos de Pruebas
```php
// config/database.php
'testing' => [
    'driver' => 'sqlite',
    'database' => ':memory:',  // Base de datos en memoria
    'prefix' => '',
],
```

## 📞 Soporte y Contacto

### Equipo Responsable
- **Desarrollador principal:** Fernando Botero
- **Equipo de calidad:** Equipo de desarrollo Aria Training
- **Contacto técnico:** Documentación en `docs/operaciones/`

### Procedimiento para Solicitar Soporte
1. **Revisar documentación:** Verificar si el problema está documentado
2. **Buscar en código:** Revisar implementación relacionada
3. **Crear issue técnico:** Documentar problema con detalles completos
4. **Asignar prioridad:** Evaluar impacto en el sistema

## 🚀 Mejores Prácticas Operativas

### 1. Mantenimiento Preventivo
- **Ejecución diaria:** Todas las pruebas antes de commits importantes
- **Limpieza semanal:** Verificar y limpiar archivos temporales
- **Revisión mensual:** Auditoría completa del sistema de pruebas

### 2. Monitoreo Proactivo
- **Alertas automáticas:** Configurar notificaciones por email
- **Dashboards:** Visualización de métricas en tiempo real (futuro)
- **Tendencias:** Seguimiento de métricas a lo largo del tiempo

### 3. Recuperación de Desastres
- **Plan de respaldo:** Múltiples niveles de backup implementados
- **Procedimientos claros:** Documentación paso a paso disponible
- **Tiempo de recuperación:** Objetivo < 30 minutos para restauración completa

## 📋 Checklist de Mantenimiento

### Checklist Diario
- [ ] ✅ **Ejecutar pruebas básicas** (pruebas de humo)
- [ ] ✅ **Verificar logs de errores** en Laravel
- [ ] ✅ **Confirmar acceso a documentación** técnica
- [ ] ✅ **Verificar estado de base de datos** de pruebas

### Checklist Semanal
- [ ] ✅ **Ejecutar suite completa de pruebas**
- [ ] ✅ **Generar reporte de cobertura**
- [ ] ✅ **Revisar métricas de rendimiento**
- [ ] ✅ **Actualizar documentación si es necesario**

### Checklist Mensual
- [ ] ✅ **Auditoría completa del sistema de pruebas**
- [ ] ✅ **Revisión de procedimientos operativos**
- [ ] ✅ **Optimización de recursos si es necesario**
- [ ] ✅ **Planificación de mejoras para próximo mes**

---

*Estos procedimientos establecen los estándares operativos para el mantenimiento continuo del sistema de pruebas de Aria Training.*
