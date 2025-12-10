<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        {{-- Header / Saludo --}}
        <div class="mb-8 flex justify-between items-end">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Dashboard</p>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                    Hola, {{ Auth::user()->nombre_1 }}
                </h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ now()->format('l, d F Y') }}</p>
            </div>
            {{-- Avatar o Perfil (Opcional) --}}
            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold">
                {{ substr(Auth::user()->nombre_1, 0, 1) }}{{ substr(Auth::user()->apellido_1, 0, 1) }}
            </div>
        </div>

        {{-- Tarjeta Principal: Entrenamiento de Hoy --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Tu Entrenamiento de Hoy
            </h2>

            @if($rutinaDia)
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-zinc-800 relative group">
                    {{-- Fondo decorativo --}}
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-all"></div>

                    <div class="p-6 relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 mb-2">
                                    {{ $rutinaDia->rutina->nombre ?? 'Rutina Personalizada' }}
                                </span>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                                    {{ $rutinaDia->nombre_dia }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $rutinaDia->rutinaEjercicios->count() }} Ejercicios programados
                                </p>
                            </div>
                        </div>

                        {{-- Lista Previa de Ejercicios (Primeros 3) --}}
                        <div class="space-y-3 mb-6">
                            @foreach($rutinaDia->rutinaEjercicios->take(3) as $ejercicio)
                                <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </div>
                                    <span class="truncate">{{ $ejercicio->ejercicio->nombre }}</span>
                                </div>
                            @endforeach
                            @if($rutinaDia->rutinaEjercicios->count() > 3)
                                <div class="text-xs text-gray-400 pl-11">
                                    + {{ $rutinaDia->rutinaEjercicios->count() - 3 }} ejercicios más...
                                </div>
                            @endif
                        </div>

                        {{-- Botón CTA --}}
                        <a href="{{ route('athlete.workout.show', $rutinaDia->id) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Comenzar Entrenamiento
                        </a>
                    </div>
                </div>
            @else
                {{-- Estado de Descanso --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-100 dark:border-zinc-800">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600 dark:text-green-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">¡Día de Descanso!</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        No tienes entrenamientos programados para hoy.<br>Recupérate y prepárate para la próxima sesión.
                    </p>
                </div>
            @endif
        </div>

        {{-- Próximos Días (Placeholder) --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Próximos Días</h2>
            <div class="space-y-3">
                {{-- Aquí iría un loop de los próximos días --}}
                <div class="bg-white dark:bg-zinc-900 p-4 rounded-xl border border-gray-100 dark:border-zinc-800 flex justify-between items-center opacity-60">
                    <div class="flex items-center gap-3">
                        <div class="text-center w-10">
                            <span class="block text-xs text-gray-400 uppercase">{{ now()->addDay()->format('D') }}</span>
                            <span class="block text-lg font-bold text-gray-900 dark:text-white">{{ now()->addDay()->format('d') }}</span>
                        </div>
                        <div class="h-8 w-px bg-gray-200 dark:bg-zinc-700"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Mañana</p>
                            <p class="text-xs text-gray-500">Ver calendario completo</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
