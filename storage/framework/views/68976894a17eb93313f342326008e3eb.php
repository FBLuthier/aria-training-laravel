<!DOCTYPE html>

<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        
        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


        
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        
        <script>
            // Verificar preferencia guardada o del sistema al cargar
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Función para alternar el tema (llamada desde el botón de navegación)
            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            }
        </script>
    </head>
    
    <body class="font-sans antialiased">
        
        
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            
            
            <?php if(session()->has('impersonator_id')): ?>
            <div class="bg-red-600 text-white px-4 py-2 text-center text-sm font-bold shadow-md relative z-50">
                Estás navegando como: <?php echo e(Auth::user()->nombre_1); ?> <?php echo e(Auth::user()->apellido_1); ?>

                <a href="<?php echo e(route('admin.impersonate.stop')); ?>" class="ml-4 underline hover:text-red-100 bg-red-700 px-3 py-1 rounded transition-colors">
                    Volver a mi cuenta
                </a>
            </div>
            <?php endif; ?>

            
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            
            <?php if(isset($header)): ?>
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            
            
            <?php if(session('status')): ?>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                    
                    <div class="p-4 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo e(session('status')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            
            <main>
                
                
                <?php echo e($slot); ?>

            </main>
        </div>
        
        <?php if (isset($component)) { $__componentOriginal7cfab914afdd05940201ca0b2cbc009b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7cfab914afdd05940201ca0b2cbc009b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.toast','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7cfab914afdd05940201ca0b2cbc009b)): ?>
<?php $attributes = $__attributesOriginal7cfab914afdd05940201ca0b2cbc009b; ?>
<?php unset($__attributesOriginal7cfab914afdd05940201ca0b2cbc009b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7cfab914afdd05940201ca0b2cbc009b)): ?>
<?php $component = $__componentOriginal7cfab914afdd05940201ca0b2cbc009b; ?>
<?php unset($__componentOriginal7cfab914afdd05940201ca0b2cbc009b); ?>
<?php endif; ?>

        
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('components.command-palette', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2767118815-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

        
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    </body>
</html><?php /**PATH C:\xampp\htdocs\aria-training\resources\views/layouts/app.blade.php ENDPATH**/ ?>