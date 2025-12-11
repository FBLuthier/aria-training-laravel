<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['field', 'currentField', 'direction' => 'asc']));

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

foreach (array_filter((['field', 'currentField', 'direction' => 'asc']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $isActive = $currentField === $field;
    $icon = $isActive ? ($direction === 'asc' ? '↑' : '↓') : '';
?>

<th <?php echo e($attributes->merge(['class' => 'px-6 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600'])); ?>>
    <button 
        wire:click="sortBy('<?php echo e($field); ?>')" 
        class="flex items-center gap-2 w-full text-left font-medium <?php echo e($isActive ? 'text-blue-600 dark:text-blue-400' : ''); ?>"
    >
        <?php echo e($slot); ?>

        <!--[if BLOCK]><![endif]--><?php if($isActive): ?>
            <span class="text-xs"><?php echo e($icon); ?></span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </button>
</th>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/components/sortable-header.blade.php ENDPATH**/ ?>