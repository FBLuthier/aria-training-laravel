

<div
    x-data="{
        toasts: [],
        nextId: 0,
        
        // Agregar un nuevo toast
        addToast(message, type = 'success', duration = 2000) {
            const id = this.nextId++;
            const toast = {
                id,
                message,
                type,
                show: false
            };
            
            this.toasts.push(toast);
            
            // Mostrar con pequeño delay para animación
            setTimeout(() => {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.toasts[index].show = true;
                }
            }, 100);
            
            // Auto-dismiss después del duration
            if (duration > 0) {
                setTimeout(() => this.removeToast(id), duration);
            }
        },
        
        // Remover un toast
        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts[index].show = false;
                // Esperar a que termine la animación antes de remover
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }
        },
        
        // Obtener clase de color según el tipo
        getBgColor(type) {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            return colors[type] || colors.info;
        },
        
        // Obtener título según el tipo
        getTitle(type) {
            const titles = {
                success: 'Éxito',
                error: 'Error',
                warning: 'Advertencia',
                info: 'Información'
            };
            return titles[type] || titles.info;
        }
    }"
    @notify.window="addToast($event.detail.message, $event.detail.type || 'success', $event.detail.duration || 2000)"
    class="fixed top-4 right-4 z-50 space-y-3 pointer-events-none"
    style="max-width: 420px;"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto w-full max-w-sm bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden"
            role="alert"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <!-- Icono -->
                    <div 
                        class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                        :class="getBgColor(toast.type)"
                    >
                        <!-- Success Icon -->
                        <svg x-show="toast.type === 'success'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        
                        <!-- Error Icon -->
                        <svg x-show="toast.type === 'error'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        
                        <!-- Warning Icon -->
                        <svg x-show="toast.type === 'warning'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        
                        <!-- Info Icon -->
                        <svg x-show="toast.type === 'info'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="ml-3 flex-1">
                        <p 
                            class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            x-text="getTitle(toast.type)"
                        ></p>
                        <p 
                            class="mt-1 text-sm text-gray-600 dark:text-gray-300"
                            x-text="toast.message"
                        ></p>
                    </div>
                    
                    <!-- Botón cerrar -->
                    <button
                        @click="removeToast(toast.id)"
                        class="ml-4 flex-shrink-0 inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                
                <!-- Barra de progreso -->
                <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1">
                    <div 
                        class="h-1 rounded-full transition-all"
                        :class="getBgColor(toast.type)"
                        x-data="{ width: 100 }"
                        x-init="
                            const duration = 2000;
                            const interval = 50;
                            const step = 100 / (duration / interval);
                            const timer = setInterval(() => {
                                width -= step;
                                if (width <= 0) {
                                    clearInterval(timer);
                                }
                            }, interval);
                        "
                        :style="`width: ${width}%`"
                    ></div>
                </div>
            </div>
        </div>
    </template>
</div>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/components/toast-container.blade.php ENDPATH**/ ?>