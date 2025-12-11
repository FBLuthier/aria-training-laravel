# Vista de Atleta (v1.6)

## 📱 Visión General

La **Vista de Atleta** es una interfaz dedicada y optimizada para dispositivos móviles (Mobile First) diseñada para el usuario final. A diferencia del panel administrativo, esta vista se centra en la simplicidad, la rapidez de ejecución y la motivación.

## 🚀 Funcionalidades Principales

### 1. Dashboard del Atleta (`/dashboard`)

Cuando un usuario con rol de **Atleta** inicia sesión, es redirigido automáticamente a esta vista personalizada (en lugar del dashboard administrativo).

*   **Tarjeta "Entrenamiento de Hoy":** Muestra de forma destacada la rutina asignada para la fecha actual.
    *   Nombre de la Rutina y del Día.
    *   Estado (Pendiente / Completado).
    *   Botón de acción principal: "Comenzar Entrenamiento".
*   **Próximos Entrenamientos:** Lista compacta de los siguientes días planificados.
*   **Navegación Simplificada:** Menú inferior o superior minimalista para acceder al Historial y Perfil.

### 2. Sesión de Entrenamiento (`Athlete\WorkoutSession`)

El núcleo de la experiencia del atleta. Es una vista interactiva (Livewire) que guía al usuario paso a paso por su rutina.

*   **Header Fijo:** Muestra el ejercicio actual y el progreso.
*   **Lista de Ejercicios:** Renderiza los bloques y ejercicios del día.
*   **Tarjeta de Ejecución:**
    *   **Prescripción:** Muestra lo que el entrenador asignó (ej. "4x10 @ 20kg").
    *   **Registro Real:** Inputs para que el atleta ingrese lo que realmente hizo (Peso y Reps).
    *   **RPE/RIR:** Campos opcionales si el entrenador activó el seguimiento de intensidad.
    *   **Check de Completado:** Al marcar una serie, se guarda automáticamente en la base de datos y se visualiza un estado de éxito (verde).
*   **Temporizador de Descanso:** (Planificado) Se activa automáticamente al completar una serie.

## 💾 Modelo de Datos: Registro de Series

La persistencia de datos se maneja a través del modelo `RegistroSerie`.

| Campo | Descripción |
| :--- | :--- |
| `rutina_ejercicio_id` | Vincula el registro con el ejercicio planificado. |
| `serie_numero` | El número de la serie (1, 2, 3...). |
| `peso` | Carga utilizada (Kg/Lb). |
| `reps` | Repeticiones completadas. |
| `rpe` | (Opcional) Tasa de Esfuerzo Percibido (1-10). |
| `rir` | (Opcional) Repeticiones en Reserva. |
| `completed_at` | Timestamp de finalización. |

## 🔄 Flujo de Trabajo

1.  **Login:** El atleta ingresa a la app.
2.  **Dashboard:** Ve "Día 1 - Pierna" asignado para hoy. Click en "Comenzar".
3.  **Sesión:**
    *   Abre el primer ejercicio (Sentadilla).
    *   Realiza la Serie 1.
    *   Ingresa "100kg" y "10 reps".
    *   Marca el checkbox ✅. (Datos guardados vía Livewire).
    *   Repite para todas las series.
4.  **Finalizar:** Al terminar todos los ejercicios, pulsa "Terminar Entrenamiento".
5.  **Resumen:** (Futuro) Pantalla de felicitación y resumen de volumen total.

## 🎨 Consideraciones de Diseño (UX)

*   **Inputs Grandes:** Fáciles de tocar con dedos sudorosos en el gimnasio.
*   **Contraste Alto:** Modo oscuro optimizado para ahorro de batería y legibilidad.
*   **Feedback Inmediato:** Animaciones al completar series para dar sensación de progreso.
*   **Evitar Teclado:** Uso de selectores o steppers donde sea posible para minimizar la escritura.
