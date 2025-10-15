# Diagrama de Arquitectura del Sistema - Aria Training

## 🏗️ Arquitectura General

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           ARIA TRAINING v1.2                           │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │                    CAPA DE PRESENTACIÓN                         │    │
│  ├─────────────────────────────────────────────────────────────────┤    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │              │
│  │  │   Blade     │  │  Livewire   │  │  Alpine.js  │  │              │
│  │  │ Templates   │  │ Componentes │  │  Frontend   │  │              │
│  │  │             │  │             │  │ Framework   │  │              │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  │              │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                                                         │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │                    CAPA DE LÓGICA DE NEGOCIO                   │    │
│  ├─────────────────────────────────────────────────────────────────┤    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │              │
│  │  │Controladores│  │   Modelos   │  │  Políticas  │  │              │
│  │  │   (MVC)     │  │ (Eloquent)  │  │Autorización│  │              │
│  │  │             │  │             │  │             │  │              │
│  │  │- Rutas      │  │- Relaciones │  │- Permisos   │  │              │
│  │  │- Validación │  │- Consultas  │  │- Acceso     │  │              │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  │              │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                                                         │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │                    CAPA DE DATOS                               │    │
│  ├─────────────────────────────────────────────────────────────────┤    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │              │
│  │  │   MariaDB   │  │Migraciones  │  │  Seeders    │  │              │
│  │  │  Database   │  │             │  │             │  │              │
│  │  │             │  │- Esquemas   │  │- Datos      │  │              │
│  │  │- Tablas     │  │- Índices    │  │  de Prueba  │  │              │
│  │  │- Relaciones │  │- Constraints│  │             │  │              │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  │              │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                                                         │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │                    CAPA DE PRUEBAS                             │    │
│  ├─────────────────────────────────────────────────────────────────┤    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │              │
│  │  │   PHPUnit   │  │ Livewire    │  │ Factories   │  │              │
│  │  │  Framework  │  │  Testing    │  │  Builder    │  │              │
│  │  │             │  │             │  │             │  │              │
│  │  │- Unit Tests │  │- Component  │  │- Datos de   │  │              │
│  │  │- Integration│  │  Testing    │  │  Prueba     │  │              │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  │              │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

## 🔧 Tecnologías Específicas

### Tecnologías de Desarrollo
| Capa | Tecnología | Versión | Propósito |
|------|------------|---------|-----------|
| **Backend** | Laravel | 10+ | Framework web PHP |
| **Frontend** | Livewire | 3+ | Componentes full-stack |
| **JavaScript** | Alpine.js | 3+ | Interacciones del lado cliente |
| **Base de Datos** | MariaDB | 10.6+ | Almacenamiento de datos |
| **Testing** | PHPUnit | 11.5+ | Framework de pruebas |

### Herramientas de Desarrollo
| Herramienta | Propósito | Uso en Proyecto |
|-------------|-----------|-----------------|
| **Composer** | Gestión de dependencias | Instalación de paquetes PHP |
| **NPM** | Gestión de assets | Compilación de recursos frontend |
| **Artisan** | CLI de Laravel | Generación de código y comandos |
| **Git** | Control de versiones | Seguimiento de cambios |
| **PHPUnit** | Ejecución de pruebas | Verificación de funcionalidades |

## 🏢 Patrón Arquitectónico

### Arquitectura por Capas

#### 1. Capa de Presentación (Frontend)
- **Blade Templates:** Estructura HTML base
- **Livewire Components:** Lógica interactiva del lado servidor
- **Alpine.js:** Mejoras de UX del lado cliente
- **Tailwind CSS:** Estilos responsivos y modernos

#### 2. Capa de Lógica de Negocio (Backend)
- **Controladores:** Manejo de rutas y peticiones HTTP
- **Modelos Eloquent:** Representación de entidades de negocio
- **Políticas:** Reglas de autorización y permisos
- **Servicios:** Lógica de negocio compleja

#### 3. Capa de Datos (Persistence)
- **MariaDB:** Motor de base de datos relacional
- **Migraciones:** Control de versiones del esquema
- **Seeders:** Población inicial de datos
- **Factories:** Generación de datos de prueba

#### 4. Capa de Pruebas (Quality Assurance)
- **PHPUnit:** Ejecución y verificación de pruebas
- **Livewire Testing:** Pruebas específicas de componentes
- **Database Transactions:** Limpieza automática de datos
- **Test Coverage:** Métricas de cobertura de código

## 🔗 Flujos de Comunicación

### Comunicación Interna
```
Usuario Browser ───┐
                  ├──► Solicitud HTTP ───┐
                  │                       ├──► Laravel Routes
                  │                       ├──► Controladores
                  │                       ├──► Modelos Eloquent
                  │                       └──► Base de Datos
                  │
                  └──► Respuesta ────────┼──► Renderizado Blade
                                          ├──► Componentes Livewire
                                          └──► JavaScript Alpine.js
```

### Flujo de Componentes Livewire
```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Usuario   │◄──►│   Blade     │◄──►│  Livewire   │
│  Interface  │    │  Template   │    │ Componente  │
└─────────────┘    └─────────────┘    └─────────────┘
                                            │
                                            ▼
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  Eventos    │◄──►│   Estado    │◄──►│   Modelo    │
│JavaScript   │    │ Componente  │    │  Eloquent   │
└─────────────┘    └─────────────┘    └─────────────┘
```

## 📊 Métricas Arquitectónicas

### Rendimiento por Capa
| Capa | Tiempo Respuesta | Estado |
|------|------------------|--------|
| **Frontend** | < 300ms (interacciones) | ✅ Óptimo |
| **Backend** | < 500ms (consultas) | ✅ Óptimo |
| **Base de Datos** | < 100ms (operaciones simples) | ✅ Óptimo |
| **Pruebas** | ~1.88s (suite completa) | ✅ Excelente |

### Escalabilidad
- **Horizontal:** Patrón API-first permite múltiples clientes
- **Vertical:** Optimización de consultas y caché
- **Base de Datos:** Índices apropiados y relaciones eficientes
- **Código:** Arquitectura modular y mantenible

## 🚀 Arquitectura Futura (Post-MVP)

### Microservicios Planificados
```
┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│  API Gateway│  │   Usuario   │  │   Rutinas   │  │  Reportes   │
│             │  │  Service    │  │  Service    │  │  Service    │
│- Autentic.  │  │             │  │             │  │             │
│- Autoriz.   │  │- CRUD       │  │- Lógica     │  │- Métricas   │
│- Rate Limit │  │- Validación │  │- Cálculos   │  │- Dashboards │
└─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘
```

### Tecnologías Futuras
- **API Gateway:** Nginx o Laravel Sanctum
- **Message Queue:** Redis o RabbitMQ
- **Cache:** Redis o Memcached
- **Monitoring:** Laravel Telescope o Sentry

## 🔒 Seguridad Arquitectónica

### Capas de Seguridad
1. **Nivel de Aplicación:** Políticas de autorización Laravel
2. **Nivel de Base de Datos:** Restricciones y permisos
3. **Nivel de Red:** Firewall y HTTPS obligatorio
4. **Nivel de Código:** Validación estricta de entrada

### Medidas OWASP Top 10
- **✅ Inyección SQL:** Protección automática por Eloquent ORM
- **✅ XSS:** Sanitización automática de salida
- **✅ CSRF:** Tokens de protección en formularios
- **✅ Autenticación rota:** Bcrypt + políticas robustas
- **✅ Exposición de datos sensibles:** Encriptación adecuada

## 📈 Métricas de Calidad Arquitectónica

### Principios SOLID Aplicados
- **✅ Responsabilidad Única:** Cada componente tiene un propósito claro
- **✅ Abierto/Cerrado:** Extensible sin modificar código existente
- **✅ Sustitución de Liskov:** Interfaces consistentes
- **✅ Segregación de Interfaces:** Dependencias mínimas
- **✅ Inversión de Dependencias:** Abstracciones apropiadas

### Métricas de Mantenibilidad
- **Complejidad Ciclomática:** Baja en componentes críticos
- **Acoplamiento:** Módulos débilmente acoplados
- **Cohesión:** Alta cohesión dentro de módulos
- **Cobertura de Pruebas:** 100% en funcionalidades críticas

---

*Este diagrama detalla la arquitectura técnica completa del sistema Aria Training, proporcionando una visión clara de los componentes y sus interacciones.*
