{{--
=========================================================================
 COMPONENTE ANÓNIMO DE TABLA REUTILIZABLE
=========================================================================
 Este componente define la estructura base para todas las tablas
 de datos de nuestra aplicación, siguiendo el principio DRY.

 Atributos:
 - $attributes: Permite pasar clases CSS y otros atributos HTML
   desde donde se llama al componente.
 Slots:
 - $thead: Contenido para la cabecera de la tabla (<thead>).
 - $tbody: Contenido para el cuerpo de la tabla (<tbody>).
--}}
<div class="relative overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400']) }}>
        
        {{-- Slot para la Cabecera de la Tabla --}}
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            {{ $thead }}
        </thead>

        {{-- Slot para el Cuerpo de la Tabla --}}
        <tbody>
            {{ $tbody }}
        </tbody>

    </table>
</div>