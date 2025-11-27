<!DOCTYPE html>
{{--
=========================================================================
 LAYOUT PRINCIPAL DE LA APLICACIÓN (PARA USUARIOS AUTENTICADOS)
=========================================================================
 Este archivo define la estructura HTML base para todas las páginas
 que requieren que un usuario haya iniciado sesión. Actúa como una
 plantilla maestra que incluye la navegación, el encabezado y el
 contenedor principal donde se inyectará el contenido de cada vista.
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- BLOQUE 1: METAETIQUETAS Y CONFIGURACIÓN DEL HEAD --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- Token CSRF: Esencial para la seguridad, protege contra ataques de falsificación de peticiones en sitios cruzados. --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Título de la página: Toma el nombre de la aplicación desde el archivo de configuración, con 'Laravel' como valor por defecto. --}}
        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Fuentes: Importa las fuentes utilizadas en el proyecto desde Google Fonts (a través de Bunny Fonts). --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Scripts y Estilos: Utiliza Vite, el compilador de assets moderno de Laravel, para incluir los archivos CSS y JS compilados. --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Estilos de Livewire (necesarios para componentes Livewire) --}}
        @livewireStyles

        {{-- Estilo para x-cloak de Alpine.js: Evita el "parpadeo" de los elementos de Alpine.js al cargar la página, ocultándolos hasta que Alpine esté listo. --}}
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    
    <body class="font-sans antialiased">
        {{-- BLOQUE 2: ESTRUCTURA PRINCIPAL DEL BODY --}}
        {{-- Contenedor principal que abarca toda la altura de la pantalla y define los colores de fondo para modo claro y oscuro. --}}
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            
            {{-- Inclusión de la barra de navegación: Carga la vista parcial que contiene el menú de navegación. --}}
            @include('layouts.navigation')

            {{-- BLOQUE 3: ENCABEZADO DE LA PÁGINA (SLOT) --}}
            {{-- @isset($header) comprueba si la vista que extiende este layout ha definido un slot llamado 'header'. --}}
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{-- Si el slot 'header' existe, su contenido se imprime aquí. Así creamos títulos de página consistentes. --}}
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- BLOQUE 4: MENSAJES DE ESTADO (FLASH MESSAGES) --}}
            {{-- Este bloque comprueba si existe un mensaje de 'status' en la sesión. --}}
            @if (session('status'))
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                    {{-- Si existe, muestra una alerta de éxito. Es útil para notificar al usuario después de una acción (ej. "Perfil actualizado"). --}}
                    <div class="p-4 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            {{-- BLOQUE 5: CONTENIDO PRINCIPAL DE LA PÁGINA (SLOT) --}}
            <main>
                {{-- Esta es la parte más importante del layout. La variable `$slot` es donde Laravel inyectará --}}
                {{-- todo el contenido de la vista específica que está usando este layout (ej. el contenido de 'dashboard.blade.php' o nuestro componente Livewire). --}}
                {{ $slot }}
            </main>
        </div>

        {{-- BLOQUE 6: CONTENEDOR DE NOTIFICACIONES TOAST --}}
        <x-toast-container />

        {{-- Scripts de Livewire (necesarios para componentes Livewire) --}}
        @livewireScripts
    </body>
</html>