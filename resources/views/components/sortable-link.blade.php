@props(['sort'])

@php
    $currentSort = request('sort');
    $currentDirection = request('direction', 'asc');
    $newDirection = ($currentSort == $sort && $currentDirection == 'asc') ? 'desc' : 'asc';
@endphp

<a href="{{ route(Route::currentRouteName(), ['sort' => $sort, 'direction' => $newDirection] + request()->except(['sort', 'direction', 'page'])) }}"
   class="flex items-center gap-2 hover:underline hover:text-blue-500 dark:hover:text-blue-400">
    
    {{-- El texto del encabezado (ej. "ID", "Nombre") --}}
    {{ $slot }}

    {{-- INICIO: Contenedor de Ancho Fijo para el Icono --}}
    <div class="w-3 h-3">
        @if ($currentSort == $sort)
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                @if ($currentDirection == 'asc')
                    {{-- Flecha Arriba (Ascendente) --}}
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                @else
                    {{-- Flecha Abajo (Descendente) --}}
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v12m0 0 4-4m-4 4L1 9"/>
                @endif
            </svg>
        @endif
    </div>
    {{-- FIN: Contenedor de Ancho Fijo --}}
</a>