# Historias de Usuario: Administrador (Versión MVP)

Este documento define las funcionalidades que el usuario de tipo "Administrador" podrá realizar para gestionar el sistema "Aria Training" en su primera versión (MVP).

---

### Gestión de Usuarios

*   **ID:** AD-01
    *   **Historia:** Como **Administrador**, quiero **poder crear, ver, editar y desactivar cuentas de Entrenador** para **gestionar qué profesionales tienen acceso para dar servicio en la plataforma**.
*   **ID:** AD-02
    *   **Historia:** Como **Administrador**, quiero **poder ver una lista de todos los usuarios del sistema** (Atletas y Entrenadores) para **tener una visión general de la actividad de la plataforma**.

---

### Gestión de Catálogos del Sistema

*   **ID:** AD-03
    *   **Historia:** Como **Administrador**, quiero **gestionar (crear, editar, eliminar) los ejercicios del catálogo global** para **mantener una base de datos de ejercicios limpia y consistente para todos los entrenadores**.
*   **ID:** AD-04
    *   **Historia:** Como **Administrador**, quiero **gestionar el catálogo de equipamiento disponible** (ej. "Barra", "Mancuerna") para **que los ejercicios puedan ser clasificados correctamente**.
*   **ID:** AD-05
    *   **Historia:** Como **Administrador**, quiero **gestionar el catálogo de grupos musculares** (ej. "Pecho", "Espalda") para **asegurar una correcta categorización de los ejercicios**.
*   **ID:** AD-06
    *   **Historia:** Como **Administrador**, quiero **gestionar los tipos de objetivos de las rutinas** (ej. "Hipertrofia", "Fuerza") para **estandarizar la creación de planes de entrenamiento**.

---

### Panel de Control

*   **ID:** AD-07
    *   **Historia:** Como **Administrador**, quiero **ver un panel de control con estadísticas básicas** (ej. número total de usuarios, número de rutinas activas) para **monitorear la salud general de la aplicación**.

---

### Herramientas Administrativas Avanzadas (v1.7) ⭐

*   **ID:** AD-08
    *   **Historia:** Como **Administrador**, quiero **poder iniciar sesión como cualquier usuario** (Impersonation) para **diagnosticar problemas que reportan los usuarios viendo exactamente lo que ellos ven**.
    *   **Implementado:** `ImpersonationController`, botón de ojo en tabla de usuarios, banner de notificación.

*   **ID:** AD-09
    *   **Historia:** Como **Administrador**, quiero **acceder rápidamente a cualquier sección del sistema usando Ctrl+K** (Command Palette) para **navegar de forma eficiente sin depender del mouse**.
    *   **Implementado:** `CommandPalette` Livewire component, búsqueda de páginas y usuarios.

*   **ID:** AD-10
    *   **Historia:** Como **Administrador**, quiero **poder restablecer la contraseña de cualquier usuario** para **ayudar a usuarios que han olvidado sus credenciales sin necesidad de acceso directo a la base de datos**.
    *   **Implementado:** Modal de reseteo con generador de contraseñas aleatorias.

*   **ID:** AD-11
    *   **Historia:** Como **Usuario**, quiero **poder subir una foto de perfil** para **personalizar mi cuenta y hacerla más reconocible**.
    *   **Implementado:** Campo `profile_photo_path` en usuarios, accessor con fallback a UI Avatars.