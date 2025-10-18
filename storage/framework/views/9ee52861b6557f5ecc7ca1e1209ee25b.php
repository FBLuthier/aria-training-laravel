<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['responsive' => true]));

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

foreach (array_filter((['responsive' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="relative overflow-x-auto <?php echo e($responsive ? 'sm:rounded-lg' : ''); ?>">
    <table <?php echo e($attributes->merge(['class' => 'w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400'])); ?>>
        
        <!--[if BLOCK]><![endif]--><?php if(isset($thead)): ?>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <?php echo e($thead); ?>

            </thead>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if(isset($tbody)): ?>
            <tbody>
                <?php echo e($tbody); ?>

            </tbody>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </table>
</div>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/components/data-table.blade.php ENDPATH**/ ?>