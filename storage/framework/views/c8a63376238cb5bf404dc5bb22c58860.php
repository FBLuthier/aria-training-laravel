<div class="min-h-screen bg-gray-50 dark:bg-black pb-20">
    
    <div class="sticky top-0 z-20 bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800 px-4 py-4 shadow-sm">
        <div class="flex justify-between items-center max-w-lg mx-auto">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="text-center">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                    <?php echo e($rutinaDia->nombre_dia); ?>

                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <?php echo e($rutinaDia->rutina->nombre); ?>

                </p>
            </div>
            <div class="w-6"></div>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-6 space-y-6">
        
        
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $rutinaDia->rutinaEjercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $re): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Determinar unidades dinámicas
                $unidadPeso = $re->unidad_peso ?? 'kg';
                $unidadReps = $re->unidad_repeticiones ?? 'reps';
                
                // Labels para headers
                $labelPeso = match($unidadPeso) {
                    'bw' => 'BW',
                    'banda' => 'Banda',
                    'kg' => 'KG',
                    'lb' => 'LB',
                    default => strtoupper($unidadPeso)
                };
                
                $labelReps = match($unidadReps) {
                    'segundos' => 'SEG',
                    'respiraciones' => 'RESP',
                    'reps' => 'REPS',
                    default => strtoupper($unidadReps)
                };
                
                // Equipo del ejercicio
                $equipoNombre = $re->ejercicio->equipo?->nombre ?? 'Peso Corporal';
            ?>

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
                
                
                <div class="p-4 border-b border-gray-100 dark:border-zinc-800 bg-gray-50/50 dark:bg-zinc-800/50">
                    <div class="flex justify-between items-start">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">
                            <?php echo e($re->ejercicio->nombre); ?> 
                            <span class="font-normal text-gray-500 dark:text-gray-400">(<?php echo e($equipoNombre); ?>)</span>
                        </h3>
                    </div>
                    
                    <!--[if BLOCK]><![endif]--><?php if($re->indicaciones): ?>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded border border-yellow-100 dark:border-yellow-900/30">
                            <span class="font-bold text-yellow-600 dark:text-yellow-500">Nota:</span> <?php echo e($re->indicaciones); ?>

                        </p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <div class="mt-2 flex gap-3 text-xs text-gray-500 dark:text-gray-400">
                        <!--[if BLOCK]><![endif]--><?php if($re->tempo): ?>
                            <span class="flex items-center gap-1 bg-gray-100 dark:bg-zinc-700 px-2 py-1 rounded">
                                ⏱ Tempo: <?php echo e($re->tempo['fase1']['tiempo'] ?? 0); ?>-<?php echo e($re->tempo['fase2']['tiempo'] ?? 0); ?>-<?php echo e($re->tempo['fase3']['tiempo'] ?? 0); ?>

                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($re->is_unilateral): ?>
                            <span class="flex items-center gap-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded">
                                ↔️ Unilateral
                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                
                <div class="p-4">
                    
                    <div class="grid gap-2 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider text-center
                                <?php echo e($re->is_unilateral ? 'grid-cols-10' : 'grid-cols-9'); ?>">
                        <div class="col-span-1">#</div>
                        <!--[if BLOCK]><![endif]--><?php if($re->is_unilateral): ?>
                            <div class="col-span-1">Lado</div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <div class="col-span-3"><?php echo e($labelPeso); ?></div>
                        <div class="col-span-3"><?php echo e($labelReps); ?></div>
                        <div class="col-span-2">✓</div>
                    </div>

                    <!--[if BLOCK]><![endif]--><?php for($i = 1; $i <= $re->series; $i++): ?>
                        <!--[if BLOCK]><![endif]--><?php if($re->is_unilateral): ?>
                            
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['left' => 'L', 'right' => 'R']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lado => $labelLado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="grid grid-cols-10 gap-2 items-center <?php echo e($lado === 'left' ? 'mb-1' : 'mb-3'); ?> last:mb-0" 
                                     wire:key="row-<?php echo e($re->id); ?>-<?php echo e($i); ?>-<?php echo e($lado); ?>">
                                    
                                    
                                    <div class="col-span-1 flex justify-center">
                                        <!--[if BLOCK]><![endif]--><?php if($lado === 'left'): ?>
                                            <span class="w-6 h-6 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-gray-400 text-xs flex items-center justify-center font-bold">
                                                <?php echo e($i); ?>

                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    
                                    <div class="col-span-1 flex justify-center">
                                        <span class="text-xs font-bold <?php echo e($lado === 'left' ? 'text-blue-500' : 'text-green-500'); ?>">
                                            <?php echo e($labelLado); ?>

                                        </span>
                                    </div>

                                    
                                    <div class="col-span-3">
                                        <input type="text" 
                                               wire:model.blur="logs.<?php echo e($re->id); ?>.<?php echo e($i); ?>.<?php echo e($lado); ?>.peso"
                                               placeholder="<?php echo e($unidadPeso !== 'bw' ? ($re->peso_sugerido ?? '') : ''); ?>"
                                               class="w-full text-center py-1.5 px-1 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none <?php echo e($unidadPeso === 'bw' ? 'bg-gray-200 dark:bg-zinc-700 cursor-not-allowed' : ''); ?>"
                                               <?php echo e(($logs[$re->id][$i]['completed'] ?? false) || $unidadPeso === 'bw' ? 'disabled' : ''); ?>

                                        >
                                    </div>

                                    
                                    <div class="col-span-3">
                                        <input type="text" 
                                               wire:model.blur="logs.<?php echo e($re->id); ?>.<?php echo e($i); ?>.<?php echo e($lado); ?>.reps"
                                               placeholder="<?php echo e($re->repeticiones ?? ''); ?>"
                                               class="w-full text-center py-1.5 px-1 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                               <?php echo e($logs[$re->id][$i]['completed'] ?? false ? 'disabled' : ''); ?>

                                        >
                                    </div>

                                    
                                    <div class="col-span-2 flex justify-center">
                                        <!--[if BLOCK]><![endif]--><?php if($lado === 'right'): ?>
                                            <button type="button"
                                                    wire:click="toggleComplete(<?php echo e($re->id); ?>, <?php echo e($i); ?>)"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 
                                                           <?php echo e(($logs[$re->id][$i]['completed'] ?? false)
                                                               ? 'bg-green-500 text-white shadow-lg shadow-green-500/30 scale-105' 
                                                               : 'bg-gray-100 dark:bg-zinc-800 text-gray-300 dark:text-gray-600 hover:bg-gray-200 dark:hover:bg-zinc-700'); ?>"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <?php else: ?>
                            
                            <div class="grid grid-cols-9 gap-2 items-center mb-3 last:mb-0" wire:key="row-<?php echo e($re->id); ?>-<?php echo e($i); ?>">
                                
                                <div class="col-span-1 flex justify-center">
                                    <span class="w-6 h-6 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-gray-400 text-xs flex items-center justify-center font-bold">
                                        <?php echo e($i); ?>

                                    </span>
                                </div>

                                
                                <div class="col-span-3">
                                    <input type="text" 
                                           wire:model.blur="logs.<?php echo e($re->id); ?>.<?php echo e($i); ?>.single.peso"
                                           placeholder="<?php echo e($unidadPeso !== 'bw' ? ($re->peso_sugerido ?? '') : ''); ?>"
                                           class="w-full text-center py-1.5 px-1 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none <?php echo e($unidadPeso === 'bw' ? 'bg-gray-200 dark:bg-zinc-700 cursor-not-allowed' : ''); ?>"
                                           <?php echo e(($logs[$re->id][$i]['completed'] ?? false) || $unidadPeso === 'bw' ? 'disabled' : ''); ?>

                                    >
                                </div>

                                
                                <div class="col-span-3">
                                    <input type="text" 
                                           wire:model.blur="logs.<?php echo e($re->id); ?>.<?php echo e($i); ?>.single.reps"
                                           placeholder="<?php echo e($re->repeticiones ?? ''); ?>"
                                           class="w-full text-center py-1.5 px-1 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                           <?php echo e($logs[$re->id][$i]['completed'] ?? false ? 'disabled' : ''); ?>

                                    >
                                </div>

                                
                                <div class="col-span-2 flex justify-center">
                                    <button type="button"
                                            wire:click="toggleComplete(<?php echo e($re->id); ?>, <?php echo e($i); ?>)"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 
                                                   <?php echo e(($logs[$re->id][$i]['completed'] ?? false)
                                                       ? 'bg-green-500 text-white shadow-lg shadow-green-500/30 scale-105' 
                                                       : 'bg-gray-100 dark:bg-zinc-800 text-gray-300 dark:text-gray-600 hover:bg-gray-200 dark:hover:bg-zinc-700'); ?>"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </div>
                            </div>
                            
                            
                            <!--[if BLOCK]><![endif]--><?php if(($re->track_rpe || $re->track_rir) && !($logs[$re->id][$i]['completed'] ?? false)): ?>
                                <div class="grid grid-cols-9 gap-2 mb-3 -mt-1">
                                    <div class="col-span-1"></div>
                                    <div class="col-span-6 flex gap-2 justify-end">
                                        <!--[if BLOCK]><![endif]--><?php if($re->track_rpe): ?>
                                            <div class="flex items-center gap-1">
                                                <span class="text-[10px] text-gray-400">RPE</span>
                                                <input type="number" step="0.5" wire:model.blur="logs.<?php echo e($re->id); ?>.<?php echo e($i); ?>.single.rpe" class="w-12 py-0.5 text-xs text-center rounded border-gray-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if($re->track_rir): ?>
                                            <div class="flex items-center gap-1">
                                                <span class="text-[10px] text-gray-400">RIR</span>
                                                <input type="number" wire:model.blur="logs.<?php echo e($re->id); ?>.<?php echo e($i); ?>.single.rir" class="w-12 py-0.5 text-xs text-center rounded border-gray-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="col-span-2"></div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                
                <div class="p-4 border-t border-gray-100 dark:border-zinc-800 bg-gray-50/30 dark:bg-zinc-800/30">
                    <textarea wire:model.blur="notasEjercicio.<?php echo e($re->id); ?>"
                              placeholder="Notas sobre este ejercicio (opcional)..."
                              class="w-full text-sm rounded-lg border-gray-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white resize-none placeholder-gray-400 dark:placeholder-gray-500"
                              rows="2"></textarea>
                    
                    
                    <button type="button" class="mt-2 text-xs text-indigo-500 hover:text-indigo-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Agregar video
                    </button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

        
        <div class="pt-4">
            <button onclick="confirm('¿Terminar entrenamiento?') || event.stopImmediatePropagation()" wire:click="finishWorkout" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-500/30 transition-all active:scale-[0.98]">
                Terminar Entrenamiento
            </button>
        </div>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/livewire/athlete/workout-session.blade.php ENDPATH**/ ?>