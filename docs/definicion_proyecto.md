# Documento de Definición del Proyecto: Aria Training (v1.3)

## Resumen Ejecutivo

El presente documento define el proyecto "Aria Training", una plataforma web diseñada para solucionar la ineficiencia en la gestión de entrenamientos entre entrenadores personales y sus atletas. El problema actual radica en el uso de herramientas desconectadas (hojas de cálculo, mensajería, cuadernos), lo que dificulta el seguimiento y la personalización. "Aria Training" centraliza este flujo de trabajo, permitiendo a los entrenadores crear y asignar rutinas de forma eficiente, y a los atletas registrar su progreso de manera clara y motivadora. La primera versión (MVP) se enfoca en las funcionalidades esenciales de gestión de usuarios, creación de rutinas y registro de entrenamientos. El sistema se construye con el framework Laravel, **utilizando una arquitectura moderna con Livewire para ofrecer una experiencia de usuario rica y reactiva, sentando las bases** para una futura escalabilidad hacia aplicaciones nativas.

### Historial de Versiones
| Versión | Fecha | Autor | Cambios Realizados |
| :--- | :--- | :--- | :--- |
| 1.0 | 2025-10-05 | Fernando Botero | Creación inicial del documento. Definición completa del alcance, requisitos y arquitectura para el MVP. |
| 1.2 | 2025-10-15 | Fernando Botero | Implementación del sistema de pruebas automatizadas. Se agregan detalles sobre el sistema completo de pruebas desarrollado siguiendo prácticas de Extreme Programming (XP). Se documenta la implementación de 11 pruebas exhaustivas con cobertura del 100% en funcionalidades críticas del módulo de gestión de equipos (AD-04). Se establece el patrón profesional para desarrollo futuro. |
| **1.3** | **2025-10-15** | **Fernando Botero** | **Implementación completa del Sistema Avanzado de Exportación de Auditoría. Se añade funcionalidad de exportación en múltiples formatos (CSV, XLSX, PDF) con selección granular de campos. Se integra PhpSpreadsheet para generación nativa de archivos Excel. Se documenta la arquitectura de componentes Livewire con Alpine.js para el modal de exportación. Se eliminan dependencias obsoletas mejorando la seguridad del sistema (0 vulnerabilidades). Actualización del stack tecnológico del proyecto.** |

---

## Sección 1: Introducción

### 1.1. Propósito del Documento
El propósito de este documento es definir y especificar los requisitos funcionales, no funcionales, el alcance y la arquitectura técnica para el desarrollo de la primera versión (MVP) del software "Aria Training". Este documento servirá como la **única fuente de la verdad** para el equipo de desarrollo, estableciendo un entendimiento común de los objetivos del proyecto.

### 1.2. Alcance del Proyecto
**Alcance del MVP (Versión 1):** La versión inicial del proyecto consistirá en una **aplicación web monolítica**, accesible a través de navegadores de escritorio modernos. Esta aplicación cubrirá todas las funcionalidades definidas para los actores Atleta, Entrenador y Administrador. **Las interfaces de gestión (paneles de administración) se construirán utilizando Laravel Livewire para proporcionar una experiencia de usuario altamente interactiva y reactiva, similar a una Single Page Application (SPA), minimizando las recargas de página completas.** Las vistas más estáticas seguirán utilizando el sistema de plantillas Laravel Blade.

**Visión y Alcance a Futuro (Post-MVP):** La arquitectura del sistema se diseña bajo un enfoque **"API-first"**. El núcleo de la aplicación (backend) se construye como un servicio independiente que expone sus funcionalidades a través de una API RESTful. Esta decisión estratégica garantiza la futura escalabilidad del producto, permitiendo el desarrollo de clientes nativos para **Windows, iOS y Android** en versiones posteriores, los cuales consumirán esta misma API central.

### 1.3. Audiencia Prevista
Este documento está dirigido a:
* **Analistas y Desarrolladores:** Para guiar el diseño técnico y la construcción del sistema.
* **Líderes de Proyecto:** Para gestionar el alcance, la planificación y los objetivos.
* **Equipo de Calidad (QA):** Para diseñar casos de prueba basados en los requisitos.
* **Stakeholders:** Para entender la visión, funcionalidad y hoja de ruta del producto.

---

## Sección 2: Descripción General y Metodología

### 2.1. Perspectiva del Producto
Aria Training es una aplicación web diseñada para servir como un ecosistema digital para la gestión del entrenamiento personal. Su objetivo es centralizar y simplificar el flujo de trabajo entre entrenadores y sus atletas, reemplazando métodos desconectados como hojas de cálculo, mensajes de texto y cuadernos. La plataforma proporciona herramientas para la creación estructurada de rutinas, el registro detallado del rendimiento y el seguimiento del progreso a lo largo del tiempo.

### 2.2. Funcionalidades Principales
El MVP de la aplicación incluirá las siguientes capacidades fundamentales:
* **Gestión de Cuentas:** Registro, autenticación y gestión de perfiles para todos los tipos de usuario.
* **Creación de Contenido de Entrenamiento:** Un catálogo centralizado de ejercicios, equipos y grupos musculares gestionado por el Administrador.
* **Diseño de Rutinas (Entrenador):** Herramientas para que los entrenadores creen plantillas de rutinas estructuradas por días.
* **Asignación y Seguimiento (Entrenador):** Funcionalidad para asignar rutinas a atletas y monitorear su historial de entrenamientos.
* **Ejecución y Registro (Atleta):** Interfaz para que los atletas visualicen su rutina diaria y registren los datos de su rendimiento.
* **Historial y Progreso (Atleta):** Acceso a un historial de entrenamientos y al progreso en ejercicios específicos.

### 2.3. Tipos de Usuarios (Actores)
El sistema define tres roles de usuario con diferentes niveles de permisos y responsabilidades:
* **Atleta:** El usuario final de la plataforma. Su principal objetivo es seguir el plan de entrenamiento asignado y registrar su progreso.
* **Entrenador:** Un profesional del fitness que utiliza la plataforma para gestionar a sus clientes, crear y asignar planes de entrenamiento.
* **Administrador:** El superusuario del sistema. Responsable de la gestión de las cuentas de los entrenadores y del mantenimiento de los catálogos de datos globales.

### 2.4. Metodología de Desarrollo
El proyecto se gestionará siguiendo una filosofía **Ágil**, adoptando prácticas del marco de trabajo **Scrum** para estructurar el desarrollo.
* **Desarrollo Iterativo en Sprints:** El trabajo se organizará en ciclos de desarrollo denominados Sprints. El primer gran objetivo será la entrega de un **Producto Mínimo Viable (MVP)**, que constituirá nuestro "Sprint 1". Futuras versiones (v1.1, v1.2, etc.) se planificarán en Sprints subsecuentes.
* **Gestión del Flujo de Trabajo:** Se utilizará un tablero visual de tareas (estilo **Kanban**) para gestionar el ciclo de vida de cada historia de usuario, desde el "Product Backlog" hasta su finalización.
* **Compromiso con la Calidad:** Se pondrá un énfasis especial en la calidad técnica del software. Se adoptarán prácticas de **Extreme Programming (XP)**, como la implementación de un conjunto de **pruebas automatizadas**, para garantizar la robustez y mantenibilidad del código.

  **Estado Actual del Sistema de Pruebas:** Se ha implementado un sistema completo de pruebas automatizadas siguiendo prácticas de Extreme Programming (XP). Se han desarrollado **11 pruebas exhaustivas** cubriendo el módulo de gestión de equipos (AD-04) con **100% de cobertura** en funcionalidades críticas. El sistema incluye pruebas de autorización, operaciones CRUD, validación de formularios, casos extremos y características avanzadas. Tiempo de ejecución promedio: ~1.88 segundos. Documentación completa disponible en `docs/pruebas/`.
* **Product Backlog:** Todas las funcionalidades futuras se registrarán y priorizarán en un **Product Backlog** centralizado, base para la planificación de futuros Sprints.

---

## Sección 3: Requisitos Funcionales

Esta sección detalla todas las funcionalidades que el software "Aria Training" debe realizar en su versión MVP. Para una descripción exhaustiva de cada historia, incluyendo su ID único, consúltese el documento específico de cada actor en la carpeta `/docs/casos_de_uso/`.

### 3.1. Requisitos del Actor: Atleta
El Atleta podrá gestionar su cuenta, visualizar su plan de entrenamiento, ejecutar y registrar los detalles de cada serie, y hacer seguimiento de su historial y progreso a lo largo del tiempo.
> *Referencia completa: `docs/casos_de_uso/casos_atleta.md`*

### 3.2. Requisitos del Actor: Entrenador
El Entrenador podrá gestionar a sus atletas, crear plantillas de rutinas, añadir ejercicios del catálogo, asignarlas a sus clientes y monitorear el progreso registrado por ellos.
> *Referencia completa: `docs/casos_de_uso/casos_entrenador.md`*

### 3.3. Requisitos del Actor: Administrador
El Administrador será responsable de la gestión de las cuentas de los Entrenadores y del mantenimiento de los catálogos de datos maestros del sistema (ejercicios, equipamiento, etc.).
> *Referencia completa: `docs/casos_de_uso/casos_administrador.md`*

---

## Sección 4: Requisitos No Funcionales

Esta sección define los atributos de calidad, los estándares técnicos y las restricciones operativas del sistema.

### 4.1. Rendimiento
* El tiempo de carga inicial de cualquier página no deberá exceder los **3 segundos**.
* **Las interacciones del usuario dentro de los módulos de gestión (ordenar tablas, buscar, paginar, abrir modales) deben tener una respuesta visual en menos de 300 milisegundos, gracias a las actualizaciones parciales de Livewire.**
* Las operaciones de base de datos comunes deben ejecutarse en menos de **500 milisegundos**.

### 4.2. Seguridad
* El acceso estará protegido por un sistema de autenticación y autorización robusto.
* Las contraseñas se almacenarán encriptadas usando el algoritmo **Bcrypt**.
* La aplicación debe estar protegida contra los riesgos del **OWASP Top 10**.

### 4.3. Usabilidad
* La interfaz debe ser intuitiva, clara y fácil de navegar.
* **La arquitectura de modales y componentes dinámicos debe proporcionar un flujo de trabajo sin interrupciones (sin recargas de página) en las tareas de gestión.**
* El flujo para registrar un entrenamiento debe ser rápido y eficiente.
* Todo el texto de la aplicación estará en español.

### 4.4. Compatibilidad
* La aplicación web debe ser compatible con las **dos últimas versiones estables** de Google Chrome, Mozilla Firefox y Apple Safari.

### 4.5. Mantenibilidad
* El código fuente deberá seguir las convenciones de estilo **PSR-12** y las mejores prácticas de Laravel.

### 4.6. Disponibilidad
* El sistema aspirará a un tiempo de actividad (uptime) del **99.5%** o superior en producción.

### 4.7. Escalabilidad
* La arquitectura API-first permitirá que el sistema crezca para soportar el aumento de usuarios y datos.

### 4.8. Registro de Sucesos (Logging)
* La aplicación deberá registrar los errores críticos y los eventos de seguridad importantes en archivos de registro (logs).

---

## Sección 5: Arquitectura y Diseño del Sistema

### 5.1. Stack Tecnológico
* **Backend:** PHP 8.1+ con Laravel 10+.
* **Frontend:** **Laravel Livewire y Alpine.js sobre plantillas Blade.**
* **Base de Datos:** MariaDB.
* **Servidor de Desarrollo:** XAMPP.
* **Gestor de Dependencias:** Composer.

### 5.2. Patrón de Arquitectura
* **Aplicación Monolítica (MVP):** El backend y el frontend residen en la misma base de código de Laravel.
* **API-first y RESTful:** La lógica de negocio se expondrá a través de una API RESTful para garantizar la escalabilidad futura.
* **Modelo-Vista-Controlador (MVC) y Component-Based:** Se aprovechará el patrón MVC nativo de Laravel para la estructura general, **pero las interfaces de usuario interactivas se construirán siguiendo una Arquitectura Basada en Componentes con Livewire.**

### 5.3. Modelo de Datos
El diseño de la base de datos se visualiza en el siguiente Diagrama Entidad-Relación (DER).
> *(Nota: no olvidar actualizar el schema de la base de datos, la version antigua está en la carpeta `db`)*

---

## Sección 6: Supuestos y Dependencias

### 6.1. Supuestos
* Se asume que los usuarios poseen las habilidades informáticas básicas para operar una aplicación web.
* Se asume que los usuarios dispondrán de una conexión a internet estable.
* Se asume que el contenido de los entrenamientos creado por los entrenadores es seguro y efectivo.

### 6.2. Dependencias
* El despliegue del sistema dependerá de un servicio de hosting compatible con PHP y MariaDB.
* El proyecto depende de Composer para la gestión de las librerías de backend.

---

## Apéndices

### Apéndice A: Glosario de Términos
* **MVP (Producto Mínimo Viable):** La primera versión de la aplicación que incluye solo las funcionalidades esenciales.
* **Agile (Ágil):** Filosofía de desarrollo enfocada en la entrega incremental y la adaptabilidad.
* **Scrum:** Marco de trabajo Ágil que organiza el desarrollo en ciclos cortos (Sprints).
* **API RESTful:** Arquitectura de comunicación estándar entre sistemas de software.
* **Monolito:** Arquitectura donde todos los componentes de la aplicación están en una sola unidad.
* **Stakeholder:** Persona o grupo con interés en el proyecto.
* **Bcrypt:** Algoritmo de hashing seguro para proteger contraseñas.
* **PSR-12:** Estándar de estilo de código para PHP.
* **Livewire:** **Framework full-stack para Laravel que permite construir interfaces dinámicas y reactivas escribiendo principalmente código PHP.**
* **Alpine.js:** **Framework de JavaScript minimalista utilizado para manejar interacciones del lado del cliente, como mostrar u ocultar menús y modales.**
* **SPA (Single Page Application):** **Aplicación web que carga una única página HTML y actualiza su contenido dinámicamente, proporcionando una experiencia de usuario más fluida y rápida. Nuestra implementación con Livewire es de tipo "SPA-like".**

### Apéndice B: Wireframes de la Interfaz (Pendiente)
Bocetos visuales de las pantallas principales del sistema. Vistas prioritarias a diseñar:
1.  Pantalla de Inicio de Sesión.
2.  Dashboard del Atleta.
3.  Pantalla de Registro de Ejercicio.
4.  Dashboard del Entrenador.
5.  Constructor de Rutinas.

### Apéndice C: Personas de Usuario

#### C.1. Atleta: "Carlos"
* **Perfil:** Joven profesional de 28 años, motivado pero con poco tiempo.
* **Necesita:** Una herramienta rápida y clara que le diga qué hacer y le permita registrar su rendimiento en segundos.

#### C.2. Entrenador: "Sofía"
* **Perfil:** Entrenadora personal de 35 años con múltiples clientes.
* **Necesita:** Una plataforma centralizada para crear plantillas, asignarlas y revisar el cumplimiento de sus clientes.