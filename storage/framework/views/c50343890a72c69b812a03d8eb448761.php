<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'show' => false,
    'maxWidth' => '2xl',
    'entangleProperty' => null,
    'name' => null,
    'focusable' => false
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'show' => false,
    'maxWidth' => '2xl',
    'entangleProperty' => null,
    'name' => null,
    'focusable' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
][$maxWidth];

// Convertir el valor de show a booleano
$showModal = filter_var($show, FILTER_VALIDATE_BOOLEAN);
?>

<!--[if BLOCK]><![endif]--><?php if($showModal): ?>
    
    <div
        class="fixed inset-0 z-50"
        style="background-color: rgba(0, 0, 0, 0.5);"
        wire:ignore.self
    >
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative w-full <?php echo e($maxWidth); ?> bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                
                <!--[if BLOCK]><![endif]--><?php if($entangleProperty): ?>
                    <button
                        type="button"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 z-10"
                        wire:click="$set('<?php echo e($entangleProperty); ?>', false)"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <div class="p-6">
                    <?php echo e($slot); ?>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/components/modal.blade.php ENDPATH**/ ?>