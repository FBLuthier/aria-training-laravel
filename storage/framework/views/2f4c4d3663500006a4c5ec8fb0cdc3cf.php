<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        
        <div class="mb-8 flex justify-between items-end">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Dashboard</p>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                    Hola, <?php echo e(Auth::user()->nombre_1); ?>

                </h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"><?php echo e(now()->format('l, d F Y')); ?></p>
            </div>
            
            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold">
                <?php echo e(substr(Auth::user()->nombre_1, 0, 1)); ?><?php echo e(substr(Auth::user()->apellido_1, 0, 1)); ?>

            </div>
        </div>

        
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Tu Entrenamiento de Hoy
            </h2>

            <?php if($rutinaDia): ?>
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-zinc-800 relative group">
                    
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-all"></div>

                    <div class="p-6 relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 mb-2">
                                    <?php echo e($rutinaDia->rutina->nombre ?? 'Rutina Personalizada'); ?>

                                </span>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                                    <?php echo e($rutinaDia->nombre_dia); ?>

                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    <?php echo e($rutinaDia->rutinaEjercicios->count()); ?> Ejercicios programados
                                </p>
                            </div>
                        </div>

                        
                        <div class="space-y-3 mb-6">
                            <?php $__currentLoopData = $rutinaDia->rutinaEjercicios->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ejercicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </div>
                                    <span class="truncate"><?php echo e($ejercicio->ejercicio->nombre); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($rutinaDia->rutinaEjercicios->count() > 3): ?>
                                <div class="text-xs text-gray-400 pl-11">
                                    + <?php echo e($rutinaDia->rutinaEjercicios->count() - 3); ?> ejercicios más...
                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <a href="<?php echo e(route('athlete.workout.show', $rutinaDia->id)); ?>" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Comenzar Entrenamiento
                        </a>
                    </div>
                </div>
            <?php else: ?>
                
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-100 dark:border-zinc-800">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600 dark:text-green-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">¡Día de Descanso!</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        No tienes entrenamientos programados para hoy.<br>Recupérate y prepárate para la próxima sesión.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Próximos Días</h2>
            <div class="space-y-3">
                
                <div class="bg-white dark:bg-zinc-900 p-4 rounded-xl border border-gray-100 dark:border-zinc-800 flex justify-between items-center opacity-60">
                    <div class="flex items-center gap-3">
                        <div class="text-center w-10">
                            <span class="block text-xs text-gray-400 uppercase"><?php echo e(now()->addDay()->format('D')); ?></span>
                            <span class="block text-lg font-bold text-gray-900 dark:text-white"><?php echo e(now()->addDay()->format('d')); ?></span>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/athlete/dashboard.blade.php ENDPATH**/ ?>