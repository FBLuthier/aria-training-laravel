<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['role']));

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

foreach (array_filter((['role']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Si role es un entero (ID), intentamos convertirlo a Enum
    if (is_int($role)) {
        $role = \App\Enums\UserRole::tryFrom($role);
    }
    
    $color = $role?->color() ?? 'gray';
    $label = $role?->label() ?? 'Desconocido';
    
    $colors = [
        'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
        'emerald' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300',
        'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
    ];
    
    $classes = $colors[$color] ?? $colors['gray'];
?>

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($classes); ?>">
    <?php echo e($label); ?>

</span>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/components/role-badge.blade.php ENDPATH**/ ?>