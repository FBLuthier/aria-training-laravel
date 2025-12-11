<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['options', 'activeValue', 'action' => 'setFilter']));

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

foreach (array_filter((['options', 'activeValue', 'action' => 'setFilter']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="mb-6 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="mr-2" role="presentation">
                <button 
                    type="button"
                    wire:click="<?php echo e($action); ?>(<?php echo e(is_null($option['value']) ? 'null' : $option['value']); ?>)"
                    class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-200 
                    <?php echo e($activeValue == $option['value'] 
                        ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500 bg-blue-50/50 dark:bg-blue-900/10' 
                        : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400 border-transparent'); ?>"
                >
                    <?php echo e($option['label']); ?>

                </button>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </ul>
</div>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/components/filter-pills.blade.php ENDPATH**/ ?>