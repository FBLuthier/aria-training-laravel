@props(['loadingTarget' => null])

<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition ease-in-out duration-150']) }}>
    @if($loadingTarget)
        <x-spinner 
            size="sm" 
            color="gray"
            wire:loading 
            wire:target="{{ $loadingTarget }}"
        />
    @endif
    <span @if($loadingTarget) wire:loading.remove wire:target="{{ $loadingTarget }}" @endif>
        {{ $slot }}
    </span>
    @if($loadingTarget)
        <span wire:loading wire:target="{{ $loadingTarget }}">Procesando...</span>
    @endif
</button>
