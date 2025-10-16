@props(['loadingTarget' => null])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition ease-in-out duration-150']) }}>
    @if($loadingTarget)
        <x-spinner 
            size="sm" 
            color="white"
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
