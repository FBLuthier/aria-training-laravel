# Diagramas de Arquitectura - Aria Training

## 🎯 Introducción

Esta sección contiene la documentación visual de la arquitectura del sistema Aria Training. Los diagramas aquí presentados ilustran los componentes principales, flujos de trabajo y relaciones entre módulos del sistema.

## 📋 Diagramas Disponibles

### 1. Arquitectura General del Sistema
```
┌─────────────────────────────────────────────────────────────────┐
│                        ARIA TRAINING v1.2                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐              │
│  │   Usuario   │  │  Livewire   │  │   Base de   │              │
│  │  Interface  │◄►│ Componentes │◄►│    Datos    │              │
│  │  (Browser)  │  │  (PHP)      │  │  (MariaDB)  │              │
│  └─────────────┘  └─────────────┘  └─────────────┘              │
│                                                                 │
│  Tecnologías: Laravel 10+, PHP 8.1+, Livewire, Alpine.js       │
│  Patrón: MVC + Component-Based Architecture                     │
└─────────────────────────────────────────────────────────────────┘
```

### 2. Flujo de Autenticación y Autorización
```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Usuario   │───►│   Login     │───►│  Políticas  │
│  (Cliente)  │    │  Formulario │    │ de Acceso   │
└─────────────┘    └─────────────┘    └─────────────┘
                                            │
                                            ▼
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│Middleware de│◄───┤   Sesión    │◄───┤   Usuario   │
│Autenticación│    │ Establecida │    │Autenticado  │
└─────────────┘    └─────────────┘    └─────────────┘
```

### 3. Modelo de Datos Simplificado (DER)
```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Users     │    │ TipoUsuario │    │   Equipos   │
├─────────────┤1   ├─────────────┤*   ├─────────────┤
│id           │◄──►│id           │    │id           │
│tipo_usuario│    │rol         │    │nombre       │
│nombre       │    └─────────────┘    │created_at   │
│email        │                       │updated_at   │
│created_at   │                       └─────────────┘
└─────────────┘
```

### 4. Arquitectura de Componentes Livewire
```
┌─────────────────────────────────────────────────────────┐
│                 Componente GestionEquipos               │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐      │
│  │   Vista     │  │   Lógica    │  │   Modelo    │      │
│  │  (Blade)    │  │  (PHP)      │  │  (Eloquent) │      │
│  │             │  │             │  │             │      │
│  │- Formulario │  │- Validación │  │- CRUD       │      │
│  │- Tabla      │  │- Eventos    │  │- Relaciones │      │
│  │- Modales    │  │- Estado     │  │- Consultas  │      │
│  └─────────────┘  └─────────────┘  └─────────────┘      │
│                                                         │
│  Comunicación: AJAX bidireccional con Alpine.js        │
└─────────────────────────────────────────────────────────┘
```

## 🔧 Tecnologías Visualizadas

### Tecnologías Backend
- **Laravel 10+:** Framework PHP principal
- **Eloquent ORM:** Mapeo objeto-relacional
- **PHPUnit:** Framework de pruebas unitarias
- **Livewire:** Componentes full-stack

### Tecnologías Frontend
- **Blade Templates:** Motor de plantillas Laravel
- **Alpine.js:** Framework JavaScript minimalista
- **Tailwind CSS:** Framework de estilos utility-first
- **JavaScript ES6+:** Lenguaje de programación del lado cliente

### Tecnologías de Base de Datos
- **MariaDB:** Sistema de gestión de bases de datos
- **Migraciones:** Control de versiones del esquema
- **Seeders:** Datos de prueba automatizados
- **Factories:** Generación de datos de prueba

## 📊 Flujos de Trabajo Principales

### Flujo CRUD de Equipos
```
Usuario Admin ───┐
                ├──► Acceso al Panel ───┐
                │                       ├──► Ver Lista de Equipos
                │                       ├──► Crear Nuevo Equipo
                │                       ├──► Editar Equipo Existente
                │                       └──► Eliminar Equipo
                │
                └──► Validaciones ──────┼──► Campos requeridos
                                        ├──► Nombres únicos
                                        ├──► Caracteres especiales
                                        └──► Límites de longitud
```

### Flujo de Autorización
```
Usuario ───┐
          ├──► Solicitud de Acceso ───┐
          │                             ├──► Verificación de Sesión
          │                             ├──► Validación de Rol
          │                             └──► Políticas de Autorización
          │
          └──► Respuesta ─────────────┼──► 200 (Acceso concedido)
                                      └──► 403 (Acceso denegado)
```

## 🎨 Convenciones de Diagramas

### Símbolos Utilizados
- **Rectángulos:** Componentes o módulos del sistema
- **Flechas:** Flujos de comunicación o dependencias
- **Círculos:** Puntos de decisión o verificación
- **Bases de datos:** Almacenamiento de datos

### Colores Estándar
- **Azul:** Componentes de interfaz de usuario
- **Verde:** Operaciones exitosas o datos válidos
- **Rojo:** Errores o validaciones fallidas
- **Naranja:** Procesos o flujos de trabajo
- **Gris:** Componentes de infraestructura

## 🔄 Próximos Diagramas a Crear

### Diagramas Pendientes para Versiones Futuras

#### Diagramas de Procesos de Negocio
1. **Flujo de Creación de Rutinas**
   - Proceso completo desde entrenador hasta atleta
   - Interacciones entre módulos

2. **Flujo de Seguimiento de Progreso**
   - Registro de entrenamientos por atletas
   - Monitoreo por entrenadores

#### Diagramas Técnicos Avanzados
3. **Arquitectura de Microservicios** (Post-MVP)
   - Separación de responsabilidades
   - Comunicación entre servicios

4. **Diagrama de Despliegue**
   - Infraestructura de producción
   - Configuración de servidores

## 📚 Referencias

### Recursos para Crear Diagramas
- **Mermaid.js:** Generación de diagramas con sintaxis markdown
- **Draw.io:** Herramienta visual para diagramas complejos
- **PlantUML:** Creación de diagramas con texto plano
- **Lucidchart:** Herramientas colaborativas de diagramación

### Estándares Seguidos
- **UML 2.0:** Unified Modeling Language para diagramas técnicos
- **C4 Model:** Context, Containers, Components, Code para arquitectura
- **BPMN:** Business Process Model and Notation para procesos

---

*Estos diagramas proporcionan una representación visual clara de la arquitectura y flujos del sistema Aria Training. Última actualización: Octubre 2025*
