import './bootstrap';

import Alpine from 'alpinejs';

// ✅ Solución final: Control total sobre Alpine
// Ahora que desactivamos inject_assets de Livewire, nosotros controlamos Alpine

window.Alpine = Alpine;
Alpine.start();

console.log('✅ Alpine inicializado y controlado por nuestra aplicación');

// Marcar que hemos manejado Alpine correctamente
window.AlpineManagedByApp = true;
