import './bootstrap';

import Alpine from 'alpinejs';

// ✅ Solución final: Control total sobre Alpine
// Ahora que desactivamos inject_assets de Livewire, nosotros controlamos Alpine

window.Alpine = Alpine;
Alpine.start();

console.log('✅ Alpine inicializado y controlado por nuestra aplicación');

// Marcar que hemos manejado Alpine correctamente
window.AlpineManagedByApp = true;

// =========================================================================
// HELPERS GLOBALES PARA NOTIFICACIONES TOAST
// =========================================================================

/**
 * Muestra una notificación toast
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo de notificación: 'success', 'error', 'warning', 'info'
 * @param {number} duration - Duración en milisegundos (0 = no auto-dismiss)
 */
window.notify = function(message, type = 'success', duration = 2000) {
    window.dispatchEvent(new CustomEvent('notify', {
        detail: { message, type, duration }
    }));
};

// Atajos para tipos específicos
window.notifySuccess = (message, duration = 2000) => window.notify(message, 'success', duration);
window.notifyError = (message, duration = 2000) => window.notify(message, 'error', duration);
window.notifyWarning = (message, duration = 2000) => window.notify(message, 'warning', duration);
window.notifyInfo = (message, duration = 2000) => window.notify(message, 'info', duration);

console.log('✅ Sistema de notificaciones Toast inicializado');
