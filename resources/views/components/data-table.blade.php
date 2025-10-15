@props(['responsive' => true])

<div class="relative overflow-x-auto {{ $responsive ? 'sm:rounded-lg' : '' }}">
    <table {{ $attributes->merge(['class' => 'w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400']) }}>
        {{-- Encabezados de la tabla --}}
        @isset($thead)
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                {{ $thead }}
            </thead>
        @endisset

        {{-- Cuerpo de la tabla --}}
        @isset($tbody)
            <tbody>
                {{ $tbody }}
            </tbody>
        @endisset
    </table>
</div>
