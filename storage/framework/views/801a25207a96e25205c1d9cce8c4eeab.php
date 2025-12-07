<div wire:key="re-<?php echo e($re->id); ?>" class="border border-gray-200 dark:border-gray-700 rounded-lg p-2 bg-gray-50 dark:bg-gray-700/50 hover:bg-white dark:hover:bg-gray-800 transition-colors">
    <div class="flex flex-wrap items-center gap-2">
        
        <div class="w-full md:w-auto md:flex-1 min-w-[150px]">
            <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200 truncate" title="<?php echo e($re->ejercicio->nombre); ?>"><?php echo e($re->ejercicio->nombre); ?></h4>
            <span class="text-[10px] text-gray-500 uppercase tracking-wider"><?php echo e($re->ejercicio->grupoMuscular->nombre ?? 'General'); ?></span>
        </div>

        
        <div class="flex items-center gap-2">
            
            <div class="w-16">
                <select wire:model.blur="ejerciciosData.<?php echo e($re->id); ?>.series"
                        wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'series', $event.target.value)"
                        class="block w-full py-1 text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        title="Series"
                >
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = range(1, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($i); ?>"><?php echo e($i); ?> Series</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            
            <div class="w-20">
                <input type="text" 
                       wire:model.blur="ejerciciosData.<?php echo e($re->id); ?>.repeticiones"
                       wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'repeticiones', $event.target.value)"
                       class="block w-full py-1 text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Reps"
                       title="Repeticiones"
                >
            </div>

            
            <div class="w-28 relative">
                <input type="text" 
                       wire:model.blur="ejerciciosData.<?php echo e($re->id); ?>.peso_sugerido"
                       wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'peso_sugerido', $event.target.value)"
                       <?php if(($ejerciciosData[$re->id]['unidad_peso'] ?? 'kg') === 'bw'): ?> disabled <?php endif; ?>
                       class="block w-full py-1 text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pr-12
                              <?php echo e(($ejerciciosData[$re->id]['unidad_peso'] ?? 'kg') === 'bw' ? 'bg-gray-200 dark:bg-gray-600 text-gray-500 border-gray-400 dark:border-gray-500 cursor-not-allowed opacity-75' : ''); ?>"
                       placeholder="Peso"
                       title="Peso"
                >
                <div class="absolute inset-y-0 right-0 flex items-center bg-white dark:bg-gray-900 rounded-r-md border-l border-gray-300 dark:border-gray-700">
                    <select wire:model.live="ejerciciosData.<?php echo e($re->id); ?>.unidad_peso"
                            wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'unidad_peso', $event.target.value)"
                            class="h-full py-0 pl-1 pr-6 text-[10px] border-0 bg-transparent text-gray-500 focus:ring-0"
                    >
                        <option value="kg">kg</option>
                        <option value="lbs">lbs</option>
                        <option value="bw">bw</option>
                    </select>
                </div>
            </div>
        </div>

        
        <div class="flex items-center gap-2 border-l border-gray-200 dark:border-gray-600 pl-2">
            <div class="flex items-center">
                <input type="checkbox" 
                       id="has_tempo_<?php echo e($re->id); ?>" 
                       wire:model.live="ejerciciosData.<?php echo e($re->id); ?>.has_tempo"
                       wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'has_tempo', $event.target.checked)"
                       class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-900 w-4 h-4"
                       title="Habilitar Tempo"
                >
                <label for="has_tempo_<?php echo e($re->id); ?>" class="ml-1 text-[10px] font-medium text-gray-500 dark:text-gray-400 cursor-pointer select-none">Tempo</label>
            </div>

            <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded px-1 py-0.5">
                
                <input type="number" 
                       wire:model.blur="ejerciciosData.<?php echo e($re->id); ?>.tempo.fase1.tiempo" 
                       wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'tempo.fase1.tiempo', $event.target.value)" 
                       <?php if(empty($ejerciciosData[$re->id]['has_tempo'])): ?> disabled <?php endif; ?>
                       class="w-8 py-0.5 text-[10px] text-center rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 p-0 
                              [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none
                              <?php echo e(empty($ejerciciosData[$re->id]['has_tempo']) ? 'bg-gray-200 dark:bg-gray-600 text-gray-500 border-gray-400 dark:border-gray-500 cursor-not-allowed opacity-75' : ''); ?>" 
                >
                <span class="text-[10px] text-gray-400">-</span>
                
                <input type="number" 
                       wire:model.blur="ejerciciosData.<?php echo e($re->id); ?>.tempo.fase2.tiempo" 
                       wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'tempo.fase2.tiempo', $event.target.value)" 
                       <?php if(empty($ejerciciosData[$re->id]['has_tempo'])): ?> disabled <?php endif; ?>
                       class="w-8 py-0.5 text-[10px] text-center rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 p-0 
                              [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none
                              <?php echo e(empty($ejerciciosData[$re->id]['has_tempo']) ? 'bg-gray-200 dark:bg-gray-600 text-gray-500 border-gray-400 dark:border-gray-500 cursor-not-allowed opacity-75' : ''); ?>" 
                >
                <span class="text-[10px] text-gray-400">-</span>
                
                <input type="number" 
                       wire:model.blur="ejerciciosData.<?php echo e($re->id); ?>.tempo.fase3.tiempo" 
                       wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'tempo.fase3.tiempo', $event.target.value)" 
                       <?php if(empty($ejerciciosData[$re->id]['has_tempo'])): ?> disabled <?php endif; ?>
                       class="w-8 py-0.5 text-[10px] text-center rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 p-0 
                              [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none
                              <?php echo e(empty($ejerciciosData[$re->id]['has_tempo']) ? 'bg-gray-200 dark:bg-gray-600 text-gray-500 border-gray-400 dark:border-gray-500 cursor-not-allowed opacity-75' : ''); ?>" 
                >
            </div>
        </div>

        
        <div class="flex-1 min-w-[150px]">
            <input type="text" 
                   wire:model.blur="ejerciciosData.<?php echo e($re->id); ?>.indicaciones"
                   wire:change="updateEjercicio(<?php echo e($re->id); ?>, 'indicaciones', $event.target.value)"
                   class="block w-full py-1 text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Notas..."
            >
        </div>

        
        <button wire:click="removeEjercicio(<?php echo e($re->id); ?>)" class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Eliminar Ejercicio">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/livewire/admin/partials/ejercicio-card.blade.php ENDPATH**/ ?>