{{-- 
=========================================================================
 HELPER PARA DISPARAR TOASTS DESDE BLADE
=========================================================================
 Componente helper para mostrar toasts basados en sesión flash
 Útil para redirecciones y acciones que no usan Livewire
--}}

@props([
    'key' => 'toast',
    'messageKey' => 'message',
    'typeKey' => 'type',
])

@if(session()->has($key))
    <div 
        x-data="{
            message: '{{ session($messageKey ?? $key) }}',
            type: '{{ session($typeKey) ?? 'success' }}'
        }"
        x-init="
            $dispatch('notify', { message: message, type: type });
            {{ session()->forget([$key, $messageKey, $typeKey]) }}
        "
    ></div>
@endif
