<div class="min-h-screen bg-gray-50 dark:bg-zinc-950">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">

        {{-- Header / Saludo --}}
        <div class="mb-8 flex justify-between items-end">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Dashboard</p>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                    Hola, {{ Auth::user()->nombre_1 }}
                </h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            {{-- Avatar --}}
            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-sm">
                {{ substr(Auth::user()->nombre_1, 0, 1) }}{{ substr(Auth::user()->apellido_1, 0, 1) }}
            </div>
        </div>

        {{-- Tarjeta Principal: Entrenamiento de Hoy --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Tu Entrenamiento de Hoy
            </h2>

            @if($this->entrenamientoHoy)
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-zinc-800 relative group">
                    {{-- Fondo decorativo --}}
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-all"></div>

                    <div class="p-6 relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 mb-2">
                                    {{ $rutina->nombre ?? 'Rutina Personalizada' }}
                                </span>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                                    {{ $this->entrenamientoHoy->nombre_dia }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $this->entrenamientoHoy->rutinaEjercicios->count() }} Ejercicios programados
                                </p>
                            </div>
                        </div>

                        {{-- Botón CTA --}}
                        <a href="{{ route('athlete.workout.show', $this->entrenamientoHoy->id) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
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

        {{-- Calendario Compacto --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg border border-gray-100 dark:border-zinc-800 overflow-hidden">
            
            {{-- Navegación Calendario --}}
            <div class="flex justify-between items-center p-4 border-b border-gray-100 dark:border-zinc-800">
                <button type="button" wire:click.prevent="changeMonth(-1)" class="p-2 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 capitalize">
                    {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->locale('es')->isoFormat('MMMM YYYY') }}
                </h3>
                
                <button type="button" wire:click.prevent="changeMonth(1)" class="p-2 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            {{-- Grid Calendario --}}
            <div class="p-4">
                {{-- Cabeceras Días --}}
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['L', 'M', 'X', 'J', 'V', 'S', 'D'] as $dayName)
                        <div class="text-center text-xs font-semibold text-gray-400 dark:text-gray-500 py-2">
                            {{ $dayName }}
                        </div>
                    @endforeach
                </div>

                {{-- Días del Mes --}}
                @php
                    $firstDay = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1);
                    $daysInMonth = $firstDay->daysInMonth;
                    $startPadding = ($firstDay->dayOfWeekIso - 1); // 0 for Monday
                @endphp

                <div class="grid grid-cols-7 gap-1">
                    {{-- Padding Inicio --}}
                    @for($i = 0; $i < $startPadding; $i++)
                        <div class="aspect-square"></div>
                    @endfor

                    {{-- Días --}}
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $dateStr = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, $day)->format('Y-m-d');
                            $isToday = $dateStr === now()->format('Y-m-d');
                            $diasDelDia = $this->diasProgramados[$dateStr] ?? collect();
                            $tieneEntrenamiento = $diasDelDia->isNotEmpty();
                            $primerDia = $diasDelDia->first();
                        @endphp

                        @if($tieneEntrenamiento)
                            {{-- Día con entrenamiento (clickeable) --}}
                            <a href="{{ route('athlete.workout.show', $primerDia->id) }}"
                               class="aspect-square flex flex-col items-center justify-center rounded-xl transition-all
                                      {{ $isToday 
                                          ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' 
                                          : 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50' 
                                      }}"
                            >
                                <span class="text-sm font-bold">{{ $day }}</span>
                                <span class="text-[9px] font-medium truncate w-full text-center px-1 {{ $isToday ? 'text-indigo-100' : 'text-indigo-500 dark:text-indigo-400' }}">
                                    {{ Str::limit($primerDia->nombre_dia, 8) }}
                                </span>
                            </a>
                        @else
                            {{-- Día sin entrenamiento --}}
                            <div class="aspect-square flex items-center justify-center rounded-xl
                                        {{ $isToday 
                                            ? 'bg-gray-200 dark:bg-zinc-700 ring-2 ring-indigo-500' 
                                            : 'text-gray-400 dark:text-gray-600' 
                                        }}"
                            >
                                <span class="text-sm {{ $isToday ? 'font-bold text-gray-700 dark:text-gray-200' : '' }}">{{ $day }}</span>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>

            {{-- Leyenda --}}
            <div class="px-4 pb-4 flex items-center justify-center gap-6 text-xs text-gray-500 dark:text-gray-400">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-indigo-100 dark:bg-indigo-900/30"></div>
                    <span>Con entrenamiento</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-indigo-600"></div>
                    <span>Hoy</span>
                </div>
            </div>
        </div>

        {{-- Info adicional si no hay rutina --}}
        @if(!$rutina)
            <div class="mt-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl p-6 text-center">
                <svg class="w-12 h-12 text-amber-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-lg font-bold text-amber-800 dark:text-amber-200 mb-2">Sin rutina activa</h3>
                <p class="text-sm text-amber-600 dark:text-amber-300">
                    Tu entrenador aún no te ha asignado una rutina activa.<br>
                    Contacta con él para comenzar tu plan de entrenamiento.
                </p>
            </div>
        @endif

    </div>
</div>
