# Documentación de Aria Training (v1.5)

Bienvenido a la documentación completa del sistema Aria Training.

---

## 📚 Guía Rápida

**¿Eres nuevo en el proyecto?** → Empieza con [Definición del Proyecto](definicion_proyecto.md)

**¿Necesitas crear un CRUD?** → Ve a [Crear Nuevo CRUD](desarrollo/crear_nuevo_crud.md)

**¿Buscas componentes reutilizables?** → Consulta [Componentes Reutilizables](arquitectura/componentes_reutilizables.md)

**¿Necesitas implementar UX profesional?** → Ver [Loading States](desarrollo/guias/loading_states.md) y [Toast Notifications](desarrollo/guias/toast_notifications.md)

**¿Tienes dudas sobre patrones?** → Lee [Buenas Prácticas](desarrollo/buenas_practicas.md)

---

## 📁 Estructura de la Documentación

### 📘 General

- **[Definición del Proyecto](definicion_proyecto.md)** - Visión general, alcance, metodología, stack tecnológico (v1.5)

---

### 🏗️ Arquitectura

Documentación sobre la estructura y componentes del sistema.

- **[Componentes Base](arquitectura/componentes_base.md)** - BaseCrudComponent, BaseModelForm, BaseQueryBuilder, BaseAdminPolicy
- **[Componentes Blade](arquitectura/componentes_blade.md)** - ⭐ **v1.5:** Plantilla optimizada y 9 componentes reutilizables (incluye loading states y toast)
- **[Componentes Reutilizables](arquitectura/componentes_reutilizables.md)** - ⭐ **v1.5:** Actions, Traits, Query Builders, 18 componentes totales
- **[Modelo de Datos](arquitectura/modelo_datos.md)** - Estructura de base de datos, relaciones, migraciones

---

### 💻 Desarrollo

Guías prácticas para desarrollar nuevas funcionalidades.

- **[Crear Nuevo CRUD](desarrollo/crear_nuevo_crud.md)** - ⭐ **v1.5:** Guía completa paso a paso + sección UX profesional
- **[Buenas Prácticas](desarrollo/buenas_practicas.md)** - Por qué usamos cada patrón y técnica

#### 📁 Guías Específicas (desarrollo/guias/)

- **[Loading States](desarrollo/guias/loading_states.md)** - ⭐ **NUEVO v1.5:** Sistema completo de estados de carga
- **[Toast Notifications](desarrollo/guias/toast_notifications.md)** - ⭐ **NUEVO v1.5:** Sistema de notificaciones elegantes

---

### ⚡ Funcionalidades

Documentación de funcionalidades principales del sistema.

- **[Selección Masiva](funcionalidades/seleccion_masiva.md)** - Sistema optimizado para operar sobre miles de registros
- **[Carga Anticipada](funcionalidades/carga_anticipada.md)** - Prevención de problemas N+1 con eager loading
- **[Exportación de Auditoría](funcionalidades/sistema_exportacion_auditoria.md)** - Sistema avanzado de exportación con filtros

---

### 📋 Casos de Uso

Flujos de usuario por rol.

- **[Casos del Administrador](casos_de_uso/casos_administrador.md)**
- **[Casos del Entrenador](casos_de_uso/casos_entrenador.md)**
- **[Casos del Atleta](casos_de_uso/casos_atleta.md)**

---

### 📊 Diagramas

Representación visual del sistema.

- **[Arquitectura del Sistema](diagramas/arquitectura_sistema.md)**
- **[Flujo de Autenticación](diagramas/flujo_autenticacion.md)**
- **[Más diagramas...](diagramas/)**

---

### ✅ Pruebas

Documentación sobre testing del sistema.

- **[Estrategia de Testing](pruebas/README.md)**
- **[Casos de prueba por módulo](pruebas/)**

---

### 📊 Métricas

Métricas de calidad y rendimiento del sistema.

- **[Calidad de Código](metricas/calidad_codigo.md)**
- **[Rendimiento de Pruebas](metricas/rendimiento_pruebas.md)**

---

## 🚀 Empezar a Desarrollar

### Setup Inicial

1. Clona el repositorio
2. Configura el entorno (ver README.md en la raíz)
3. Lee la [Definición del Proyecto](definicion_proyecto.md)
4. Familiarízate con los [Componentes Reutilizables](arquitectura/componentes_reutilizables.md)
5. Revisa el [Patrón de Commits](../CONTRIBUTING.md)

### Crear tu Primer CRUD

1. Lee [Buenas Prácticas](desarrollo/buenas_practicas.md) para entender el "por qué"
2. Sigue la [Guía de Crear CRUD](desarrollo/crear_nuevo_crud.md) paso a paso
3. Implementa [Loading States](desarrollo/guias/loading_states.md) para mejor UX
4. Agrega [Toast Notifications](desarrollo/guias/toast_notifications.md) para feedback visual
5. Usa el [Modelo de Datos](arquitectura/modelo_datos.md) como referencia

### Implementar Funcionalidades Avanzadas

- **Selección masiva:** [Selección Masiva](funcionalidades/seleccion_masiva.md)
- **Optimizar queries:** [Carga Anticipada](funcionalidades/carga_anticipada.md)
- **Exportar datos:** [Exportación de Auditoría](funcionalidades/sistema_exportacion_auditoria.md)
- **Loading states:** [Estados de Carga](desarrollo/guias/loading_states.md) ⭐ v1.5
- **Notificaciones:** [Sistema de Toast](desarrollo/guias/toast_notifications.md) ⭐ v1.5

---

## 📖 Convenciones del Proyecto

### Nombres de Archivos
- Documentos en español
- snake_case para archivos markdown
- MAYUSCULAS para documentos principales (README, INDICE)

### Estructura de Carpetas
```
docs/
├── INDICE.md                    (este archivo)
├── definicion_proyecto.md       (documento maestro v1.5)
├── arquitectura/                (estructura del sistema)
├── desarrollo/                  (guías de desarrollo)
│   └── guias/                  (guías específicas)
├── funcionalidades/             (documentación de features)
├── casos_de_uso/                (flujos por rol)
├── diagramas/                   (representación visual)
├── pruebas/                     (testing)
└── metricas/                    (calidad y rendimiento)
```

---

## 🆘 ¿Necesitas Ayuda?

### Por Tipo de Tarea

**Crear algo nuevo:**
- CRUD → [Crear Nuevo CRUD](desarrollo/crear_nuevo_crud.md)
- Loading states → [Loading States](desarrollo/guias/loading_states.md)
- Notificaciones → [Toast Notifications](desarrollo/guias/toast_notifications.md)
- Acción de negocio → [Componentes Reutilizables](arquitectura/componentes_reutilizables.md#actions)
- Componente Blade → [Componentes Blade](arquitectura/componentes_blade.md)

**Optimizar código:**
- Queries lentas → [Carga Anticipada](funcionalidades/carga_anticipada.md)
- Código duplicado → [Componentes Reutilizables](arquitectura/componentes_reutilizables.md)
- Operaciones masivas → [Selección Masiva](funcionalidades/seleccion_masiva.md)

**Entender el sistema:**
- Visión general → [Definición del Proyecto](definicion_proyecto.md)
- Por qué usamos X → [Buenas Prácticas](desarrollo/buenas_practicas.md)
- Cómo está estructurado → [Modelo de Datos](arquitectura/modelo_datos.md)

---

## 🤝 Contribuir al Proyecto

**Antes de hacer commits:**
- Lee el [Patrón de Commits](../CONTRIBUTING.md) para mantener consistencia
- Sigue las [Buenas Prácticas](desarrollo/buenas_practicas.md) del código
- Asegúrate de que los tests pasen

---

## 🔄 Última Actualización

**Fecha:** 2025-10-17  
**Versión de la documentación:** 2.2  
**Versión del proyecto:** 1.5

**Cambios recientes v1.5:**
- ⭐ **Sistema completo de Loading States:** 3 componentes nuevos (spinner, loading-overlay, loading-state)
- ⭐ **Sistema de Notificaciones Toast:** 2 componentes nuevos (toast-container, toast-trigger)
- ✅ Actualizado documento maestro definicion_proyecto.md con historial v1.5
- ✅ Documentados 5 nuevos componentes Blade de UX (total: 18 componentes reutilizables)
- ✅ Agregada sección "Paso 6: Mejorar UX" en guía de crear CRUD
- ✅ Guías técnicas completas: loading_states.md (483 líneas) y toast_notifications.md (629 líneas)
- ✅ Actualizado CHANGELOG.md con entrada consolidada v1.5
- ✅ Componentes de botones mejorados con prop `loadingTarget`
- ✅ Helpers globales JavaScript disponibles en toda la aplicación

**Impacto medible:**
- ✅ 5 nuevos componentes Blade para UX profesional
- ✅ Experiencia de usuario significativamente mejorada
- ✅ Prevención automática de doble-click
- ✅ Feedback visual inmediato en todas las operaciones
- ✅ Sistema completamente documentado y reutilizable
- ✅ Tiempo de implementación UX: ~5-10 minutos adicionales por vista

---

## 📝 Contribuir a la Documentación

Si encuentras algo desactualizado o confuso:
1. Actualiza el documento correspondiente
2. Actualiza este índice si agregaste/eliminaste documentos
3. Mantén el mismo formato y estructura
4. Sigue el patrón de commits establecido

---

*Documentación generada para Aria Training - Sistema de gestión de entrenamientos personalizados*
