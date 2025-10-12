# Product Backlog de Aria Training

Este documento contiene todas las funcionalidades (features), mejoras y correcciones, priorizadas para guiar la adición de nuevas funciones.

---

### Funcionalidades (Features)

* **ID:** `F-001`
    * **User Story:** Como **Entrenador**, quiero poder **crear mis propios ejercicios personalizados** (visibles solo para mí y mis atletas), para no depender del Administrador y tener más flexibilidad al diseñar rutinas.
    * **Prioridad:** Media (Planificado para V2)
    * **Estado:** Pendiente
    * **Nota:** La lista de ejercicios personalizados debe estar aislada de la vista de otros entrenadores.
    * **Nota Técnica:** **La interfaz de gestión para este CRUD deberá implementarse como un componente de Livewire, siguiendo el patrón establecido en `GestionarEquipos` para una experiencia de usuario reactiva y consistente.**

* **ID:** `F-002`
    * **User Story:** Como **Atleta**, quiero poder **ver mi rutina asignada para el día de hoy** de forma clara y sencilla para saber qué ejercicios me corresponden.
    * **Prioridad:** Alta (Crítico para el MVP)
    * **Estado:** Pendiente
    * **Nota Técnica:** **La vista principal podría ser un componente de Livewire para permitir futuras interacciones dinámicas (ej. marcar ejercicios como completados) sin recargar la página.**

---