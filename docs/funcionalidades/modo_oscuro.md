# Modo Oscuro (Dark Mode) - v1.6

## 🌑 Descripción General

Aria Training implementa un sistema de **Modo Oscuro** nativo utilizando las capacidades de **Tailwind CSS** y persistencia local. El objetivo es reducir la fatiga visual y ofrecer una estética moderna y profesional, adaptable a las preferencias del usuario.

## ⚙️ Implementación Técnica

### Configuración Tailwind

El modo oscuro está configurado en `tailwind.config.js` utilizando la estrategia de **clase**:

```javascript
module.exports = {
    darkMode: 'class', // Activa el modo oscuro al añadir la clase 'dark' al tag <html>
    // ...
}
```

Esto permite un control manual sobre el tema, en lugar de depender únicamente de la preferencia del sistema operativo (`media`).

### Persistencia (LocalStorage)

La preferencia del usuario se guarda en el `localStorage` del navegador bajo la clave `theme`.

*   **Valor `dark`:** Activa el modo oscuro.
*   **Valor `light`:** Activa el modo claro.
*   **Sin valor:** Se respeta la preferencia del sistema operativo (`window.matchMedia`).

### Script de Inicialización

Para evitar el "flicker" (parpadeo) al cargar la página, se ejecuta un script bloqueante en el `<head>` de `app.blade.php` y `guest.blade.php`:

```javascript
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark')
} else {
    document.documentElement.classList.remove('dark')
}
```

Este script verifica la preferencia antes de que el DOM se renderice por completo, asegurando que el tema correcto se aplique instantáneamente.

## 🎨 Guía de Estilos

Para garantizar la consistencia en modo oscuro, seguimos estas convenciones de colores (basadas en la paleta `zinc` y `gray` de Tailwind):

| Elemento | Modo Claro (`bg-`) | Modo Oscuro (`dark:bg-`) | Texto Claro (`text-`) | Texto Oscuro (`dark:text-`) |
| :--- | :--- | :--- | :--- | :--- |
| **Fondo Principal** | `gray-50` | `black` | `gray-900` | `white` |
| **Tarjetas / Paneles** | `white` | `zinc-900` | `gray-800` | `gray-100` |
| **Bordes** | `gray-200` | `zinc-800` | - | - |
| **Inputs** | `gray-50` | `zinc-800` | `gray-900` | `white` |
| **Dropdowns** | `white` | `zinc-800` | `gray-700` | `gray-300` |

### Componentes Clave

*   **Botón Toggle:** Ubicado en el `Navigation Menu`. Alterna la clase `dark` en `<html>` y actualiza `localStorage`.
*   **Tablas:** Usan `dark:bg-zinc-900` para filas y `dark:text-gray-300` para contenido.
*   **Modales:** Fondo `dark:bg-zinc-900` con bordes sutiles `dark:border-zinc-700`.

## ⚠️ Consideraciones de Desarrollo

Al crear nuevas vistas o componentes:

1.  **Prefijo `dark:`:** Siempre incluir variantes `dark:` para colores de fondo, texto y bordes.
2.  **Contraste:** Verificar que el texto sea legible en ambos modos. Evitar gris oscuro sobre fondo oscuro.
3.  **Bordes:** Los bordes son cruciales en modo oscuro para separar secciones, ya que las sombras son menos visibles. Usar `dark:border-zinc-800`.
4.  **Imágenes/Iconos:** Asegurarse de que los iconos (SVG) tengan `currentColor` o clases adaptativas.
