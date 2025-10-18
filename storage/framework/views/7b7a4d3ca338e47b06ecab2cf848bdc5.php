<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'target' => null,
    'message' => 'Cargando...',
    'inline' => false
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
    'target' => null,
    'message' => 'Cargando...',
    'inline' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!--[if BLOCK]><![endif]--><?php if($inline): ?>
    
    <div 
        <?php echo e($attributes->merge(['class' => 'flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400'])); ?>

        <?php if($target): ?>
            wire:loading.flex
            wire:target="<?php echo e($target); ?>"
        <?php else: ?>
            wire:loading.flex
        <?php endif; ?>
        style="display: none;"
    >
        <?php if (isset($component)) { $__componentOriginalf26909af655deaf31c8e20175813a5a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf26909af655deaf31c8e20175813a5a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner','data' => ['size' => 'sm','color' => 'gray']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','color' => 'gray']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf26909af655deaf31c8e20175813a5a0)): ?>
<?php $attributes = $__attributesOriginalf26909af655deaf31c8e20175813a5a0; ?>
<?php unset($__attributesOriginalf26909af655deaf31c8e20175813a5a0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf26909af655deaf31c8e20175813a5a0)): ?>
<?php $component = $__componentOriginalf26909af655deaf31c8e20175813a5a0; ?>
<?php unset($__componentOriginalf26909af655deaf31c8e20175813a5a0); ?>
<?php endif; ?>
        <span><?php echo e($message); ?></span>
    </div>
<?php else: ?>
    
    <div 
        <?php echo e($attributes->merge(['class' => 'flex items-center justify-center gap-3 p-4 text-gray-600 dark:text-gray-400'])); ?>

        <?php if($target): ?>
            wire:loading.flex
            wire:target="<?php echo e($target); ?>"
        <?php else: ?>
            wire:loading.flex
        <?php endif; ?>
        style="display: none;"
    >
        <?php if (isset($component)) { $__componentOriginalf26909af655deaf31c8e20175813a5a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf26909af655deaf31c8e20175813a5a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner','data' => ['size' => 'md','color' => 'gray']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','color' => 'gray']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf26909af655deaf31c8e20175813a5a0)): ?>
<?php $attributes = $__attributesOriginalf26909af655deaf31c8e20175813a5a0; ?>
<?php unset($__attributesOriginalf26909af655deaf31c8e20175813a5a0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf26909af655deaf31c8e20175813a5a0)): ?>
<?php $component = $__componentOriginalf26909af655deaf31c8e20175813a5a0; ?>
<?php unset($__componentOriginalf26909af655deaf31c8e20175813a5a0); ?>
<?php endif; ?>
        <span class="font-medium"><?php echo e($message); ?></span>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/components/loading-state.blade.php ENDPATH**/ ?>