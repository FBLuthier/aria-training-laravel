@props(['loadingTarget' => null])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition ease-in-out duration-150']) }}>
    @if($loadingTarget)
        <x-spinner 
            size="sm" 
            color="white"
            wire:loading 
            wire:target="{{ $loadingTarget }}"
            class="dark:text-gray-800"
        />
    @endif
    <span @if($loadingTarget) wire:loading.remove wire:target="{{ $loadingTarget }}" @endif>
        {{ $slot }}
    </span>
    @if($loadingTarget)
        <span wire:loading wire:target="{{ $loadingTarget }}">Procesando...</span>
    @endif
</button>
