# Patrón de Commits - Aria Training

## 🎯 Estándar de Mensajes de Commit

El proyecto Aria Training utiliza un estándar estricto para mensajes de commit que asegura claridad, trazabilidad y mantenimiento efectivo del historial de versiones.

## 📋 Formato Estándar

### Estructura de Mensaje de Commit
```
tipo: descripción corta en español

why: explicación del motivo del cambio
what: descripción detallada de lo implementado
impact: impacto en el proyecto y próximos pasos

```

### Campos Obligatorios
1. **tipo:** Categoría del cambio (feat, fix, docs, etc.)
2. **why:** Razón/justificación del cambio
3. **what:** Descripción técnica de lo implementado
4. **impact:** Consecuencias y acciones futuras

## 🏷️ Tipos de Commit

### Tipos Principales
| Tipo | Descripción | Ejemplo |
|------|-------------|---------|
| **feat** | Nueva funcionalidad | `feat: implementar gestión de equipos` |
| **fix** | Corrección de bug | `fix: corregir error 403 en autorización` |
| **docs** | Cambios en documentación | `docs: actualizar guía de instalación` |
| **refactor** | Refactorización de código | `refactor: mejorar estructura de componentes` |
| **test** | Pruebas y testing | `test: agregar pruebas de integración` |
| **chore** | Tareas de mantenimiento | `chore: actualizar dependencias` |

### Subtipos Específicos
| Subtipo | Uso | Ejemplo |
|---------|-----|---------|
| **security** | Cambios de seguridad | `fix: security: parchear vulnerabilidad XSS` |
| **performance** | Optimizaciones | `feat: performance: optimizar consultas BD` |
| **ui** | Cambios de interfaz | `feat: ui: mejorar diseño responsivo` |
| **api** | Cambios de API | `feat: api: nuevo endpoint de usuarios` |

## 📝 Ejemplos de Mensajes Completos

### Ejemplo 1: Nueva Funcionalidad
```
feat: implementar sistema de pruebas automatizadas

why: establecer estándares profesionales de calidad y prevenir regresiones en el módulo de gestión de equipos

what:
- Crear estructura completa de documentación en español (9 archivos)
- Implementar 11 pruebas exhaustivas cubriendo autorización, CRUD y casos extremos
- Establecer patrón profesional de Livewire Testing para desarrollo futuro
- Lograr cobertura del 100% en funcionalidades críticas del módulo AD-04

impact:
- Sistema de pruebas completamente operativo y documentado
- Patrón establecido para extensión a otros módulos
- Confianza absoluta en la calidad del código desarrollado
- Base sólida para implementación de funcionalidades futuras
```

### Ejemplo 2: Corrección de Bug
```
fix: corregir error 403 en autorización de equipos

why: usuarios administradores no pueden acceder al módulo de gestión de equipos debido a políticas de autorización incorrectas

what:
- Identificar problema en políticas de autorización del modelo Equipo
- Refactorizar claves foráneas para seguir convenciones Laravel (id_tabla → tabla_id)
- Actualizar relaciones en modelos afectados (Rutina, Ejercicio, etc.)
- Verificar funcionamiento correcto con pruebas automatizadas

impact:
- Acceso administrativo completamente restaurado
- Relaciones de base de datos siguiendo estándares profesionales
- Prevención de errores similares en desarrollo futuro
- Documentación actualizada con cambios implementados
```

### Ejemplo 3: Documentación
```
docs: crear documentación completa del sistema de pruebas

why: establecer documentación técnica profesional para el sistema de pruebas implementado y facilitar mantenimiento futuro

what:
- Crear estructura organizada de documentación en español (9 archivos)
- Documentar 11 pruebas específicas con detalles técnicos completos
- Establecer guías de desarrollo y mejores prácticas para pruebas futuras
- Crear métricas de cobertura y rendimiento del sistema de pruebas

impact:
- Documentación técnica completa y profesional disponible
- Facilita onboarding de nuevos desarrolladores
- Mantenimiento y extensión del sistema de pruebas documentado
- Estándares establecidos para documentación futura
```

## 🚫 Errores Comunes a Evitar

### Mensajes Incorrectos
```bash
# ❌ Demasiado corto e impreciso
git commit -m "arreglado"

# ❌ En inglés (proyecto en español)
git commit -m "add equipment management"

# ❌ Sin estructura clara
git commit -m "varios cambios en el código"

# ❌ Sin información de contexto
git commit -m "fix: corregir error"
```

### Mensajes Correctos
```bash
# ✅ Descriptivo y estructurado
git commit -m "feat: implementar gestión de equipos

why: solucionar ineficiencia en gestión de entrenamientos con herramientas desconectadas

what:
- Crear componente Livewire para gestión administrativa
- Implementar operaciones CRUD completas con autorización
- Desarrollar pruebas automatizadas con cobertura del 100%
- Establecer patrón profesional para módulos futuros

impact:
- Funcionalidad completa de gestión de equipos operativa
- Patrón establecido para desarrollo de otros módulos
- Sistema de pruebas asegurando calidad continua
- Base sólida para implementación de funcionalidades avanzadas"
```

## 📊 Convenciones de Mensajes

### Descripción Corta (tipo: descripción)
- **Máximo 50 caracteres** para la primera línea
- **Verbo en infinitivo** para acciones
- **Nombre técnico correcto** de funcionalidades
- **Sin punto final**

### Campos why, what, impact
- **why:** Máximo 2-3 líneas explicando el motivo
- **what:** Descripción técnica detallada (puede tener listas)
- **impact:** Consecuencias y próximos pasos

## 🔧 Herramientas de Soporte

### Validación Automática
```bash
# Comando para validar formato de commit (futuro)
npm run commit # Si se implementa husky o similar
```

### Templates de Commit
```bash
# Template sugerido para mensajes largos
tipo: descripción corta

why: explicación del motivo

what:
- detalle técnico 1
- detalle técnico 2
- implementación específica

impact:
- consecuencia 1
- acción futura 1
- mejora lograda
```

## 📈 Métricas de Calidad de Commits

### Métricas Objetivo
| Métrica | Objetivo | Estado Actual |
|---------|----------|---------------|
| **Longitud promedio de mensaje** | 100-200 caracteres | ✅ **Cumplido** |
| **Commits con estructura completa** | 100% | ✅ **Cumplido** |
| **Commits en español** | 100% | ✅ **Cumplido** |
| **Descripciones claras** | 100% | ✅ **Cumplido** |

### Checklist para Commits
- [ ] ✅ **Tipo correcto** asignado
- [ ] ✅ **Descripción corta** clara y concisa
- [ ] ✅ **Campo why** explica el motivo
- [ ] ✅ **Campo what** describe técnicamente los cambios
- [ ] ✅ **Campo impact** menciona consecuencias
- [ ] ✅ **Mensaje en español** completo
- [ ] ✅ **Cambios relacionados** agrupados apropiadamente

## 🚀 Ejemplos Prácticos del Proyecto

### Commits Implementados en Aria Training

#### Commit de Funcionalidad (Ejemplo Real)
```
feat: implementar sistema de pruebas automatizadas

why: establecer estándares profesionales de calidad y prevenir regresiones en el módulo de gestión de equipos

what:
- Crear estructura completa de documentación en español (9 archivos)
- Implementar 11 pruebas exhaustivas cubriendo autorización, CRUD y casos extremos
- Establecer patrón profesional de Livewire Testing para desarrollo futuro
- Lograr cobertura del 100% en funcionalidades críticas del módulo AD-04

impact:
- Sistema de pruebas completamente operativo y documentado
- Patrón establecido para extensión a otros módulos
- Confianza absoluta en la calidad del código desarrollado
- Base sólida para implementación de funcionalidades futuras
```

#### Commit de Corrección (Ejemplo Real)
```
fix: corregir error 403 en autorización de equipos

why: usuarios administradores no pueden acceder al módulo de gestión de equipos debido a políticas de autorización incorrectas

what:
- Identificar problema en políticas de autorización del modelo Equipo
- Refactorizar claves foráneas para seguir convenciones Laravel (id_tabla → tabla_id)
- Actualizar relaciones en modelos afectados (Rutina, Ejercicio, etc.)
- Verificar funcionamiento correcto con pruebas automatizadas

impact:
- Acceso administrativo completamente restaurado
- Relaciones de base de datos siguiendo estándares profesionales
- Prevención de errores similares en desarrollo futuro
- Documentación actualizada con cambios implementados
```

## 📚 Recursos y Referencias

### Estándares Seguidos
- [Conventional Commits](https://conventionalcommits.org/) - Especificación de commits convencionales
- [Semantic Versioning](https://semver.org/) - Versionado semántico
- [Laravel Best Practices](https://laravel.com/docs/best-practices) - Mejores prácticas del framework

### Herramientas Recomendadas
- **Git Flow:** Estrategia de branching
- **Git Hooks:** Automatización de validaciones
- **Git LFS:** Para archivos grandes (imágenes, etc.)

---

*Este patrón establece los estándares para mensajes de commit claros, informativos y profesionales en el proyecto Aria Training.*
