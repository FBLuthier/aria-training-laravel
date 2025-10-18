<div>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Auditoría</h1>
                <p class="text-gray-600 mt-2">Registro de todas las acciones realizadas en el sistema</p>
            </div>
            <div class="flex gap-2">
                            <!-- BOTÓN PARA EXPORTAR -->
                            <button
                                wire:click="openExportModal"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 disabled:opacity-50"
                                wire:loading.attr="disabled"
                                wire:target="exportWithOptions">
                                <?php if (isset($component)) { $__componentOriginalf26909af655deaf31c8e20175813a5a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf26909af655deaf31c8e20175813a5a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner','data' => ['size' => 'sm','color' => 'white','wire:loading' => true,'wire:target' => 'exportWithOptions','style' => 'display: none;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','color' => 'white','wire:loading' => true,'wire:target' => 'exportWithOptions','style' => 'display: none;']); ?>
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
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="exportWithOptions">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l4-4m-4 4l-4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="exportWithOptions">Exportar</span>
                                <span wire:loading wire:target="exportWithOptions" style="display: none;">Exportando...</span>
                            </button>
                <button
                    wire:click="clearFilters"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 disabled:opacity-50"
                    wire:loading.attr="disabled"
                    wire:target="clearFilters"
                >
                    <?php if (isset($component)) { $__componentOriginalf26909af655deaf31c8e20175813a5a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf26909af655deaf31c8e20175813a5a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner','data' => ['size' => 'sm','color' => 'white','wire:loading' => true,'wire:target' => 'clearFilters','style' => 'display: none;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','color' => 'white','wire:loading' => true,'wire:target' => 'clearFilters','style' => 'display: none;']); ?>
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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="clearFilters">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Limpiar Filtros</span>
                </button>
            </div>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Filtros de Búsqueda</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Búsqueda General -->
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Búsqueda General</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por acción, modelo, IP o usuario..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <div class="absolute right-3 top-10">
                    <?php if (isset($component)) { $__componentOriginalf26909af655deaf31c8e20175813a5a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf26909af655deaf31c8e20175813a5a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner','data' => ['size' => 'sm','color' => 'gray','wire:loading' => true,'wire:target' => 'search','style' => 'display: none;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','color' => 'gray','wire:loading' => true,'wire:target' => 'search','style' => 'display: none;']); ?>
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
                </div>
            </div>

            <!-- Filtro por Acción -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Acción</label>
                <select
                    wire:model.live="actionFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Todas las acciones</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Filtro por Modelo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Modelo</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="modelFilter"
                    placeholder="Ej: App\Models\Equipo"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Filtro por Usuario -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                <select
                    wire:model.live="userFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Todos los usuarios</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->nombre_1); ?> <?php echo e($user->apellido_1); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
        </div>

        <!-- Filtros de Fecha -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                <input
                    type="date"
                    wire:model.live="startDate"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                <input
                    type="date"
                    wire:model.live="endDate"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>
    </div>

    <!-- TABLA DE RESULTADOS -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden relative">
        
        <div 
            wire:loading 
            wire:target="search,actionFilter,modelFilter,userFilter,startDate,endDate,clearFilters,sortBy,gotoPage,previousPage,nextPage"
            class="absolute inset-0 bg-white/70 dark:bg-gray-900/70 z-10 flex items-center justify-center"
            style="display: none;"
        >
            <div class="text-center">
                <?php if (isset($component)) { $__componentOriginalf26909af655deaf31c8e20175813a5a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf26909af655deaf31c8e20175813a5a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner','data' => ['size' => 'lg','color' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'lg','color' => 'primary']); ?>
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
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('created_at')"
                        >
                            <div class="flex items-center gap-1">
                                Fecha
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'created_at'): ?>
                                    <svg class="w-4 h-4 <?php echo e($sortDirection === 'asc' ? 'rotate-180' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                    </svg>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuario
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('action')"
                        >
                            <div class="flex items-center gap-1">
                                Acción
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'action'): ?>
                                    <svg class="w-4 h-4 <?php echo e($sortDirection === 'asc' ? 'rotate-180' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                    </svg>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Modelo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID Registro
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($log->created_at->format('d/m/Y H:i:s')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($log->user?->nombre_1); ?> <?php echo e($log->user?->apellido_1); ?>

                                <div class="text-xs text-gray-500"><?php echo e($log->user?->correo); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php if($log->action === 'create'): ?> bg-green-100 text-green-800
                                    <?php elseif($log->action === 'update'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($log->action === 'delete'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($log->action === 'restore'): ?> bg-purple-100 text-purple-800
                                    <?php else: ?> bg-red-100 text-red-800
                                    <?php endif; ?>">
                                    <?php echo e($actions[$log->action] ?? $log->action); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(class_basename($log->model_type)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($log->model_id); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($log->ip_address); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button
                                    wire:click="showDetailsFor(<?php echo e($log->id); ?>)"
                                    class="text-indigo-600 hover:text-indigo-900 inline-flex items-center gap-1"
                                >
                                    <?php if (isset($component)) { $__componentOriginalf26909af655deaf31c8e20175813a5a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf26909af655deaf31c8e20175813a5a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner','data' => ['size' => 'xs','color' => 'primary','wire:loading' => true,'wire:target' => 'showDetailsFor('.e($log->id).')','style' => 'display: none;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xs','color' => 'primary','wire:loading' => true,'wire:target' => 'showDetailsFor('.e($log->id).')','style' => 'display: none;']); ?>
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
                                    <span><?php echo e($detailId === $log->id ? 'Ocultar' : 'Ver'); ?> Detalles</span>
                                </button>
                            </td>
                        </tr>

                        <!-- FILA DE DETALLES -->
                        <!--[if BLOCK]><![endif]--><?php if($detailId === $log->id): ?>
                            <tr class="bg-gray-50">
                                <td colspan="7" class="px-6 py-4">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-3">
                                                <h4 class="font-semibold text-gray-900">Detalles del Cambio</h4>
                                                <!--[if BLOCK]><![endif]--><?php if($log->action === 'create'): ?>
                                                    <span class="font-medium text-green-700 text-lg">Registro Creado</span>
                                                <?php elseif($log->action === 'update'): ?>
                                                    <span class="font-medium text-blue-700 text-lg">Registro Actualizado</span>
                                                <?php elseif($log->action === 'delete'): ?>
                                                    <span class="font-medium text-yellow-700 text-lg">Registro Eliminado</span>
                                                <?php elseif($log->action === 'restore'): ?>
                                                    <span class="font-medium text-purple-700 text-lg">Registro Restaurado</span>
                                                <?php elseif($log->action === 'force_delete'): ?>
                                                    <span class="font-medium text-red-700 text-lg">Registro Eliminado Permanentemente</span>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </div>

                                        <!--[if BLOCK]><![endif]--><?php if($log->action === 'create'): ?>
                                            <div>
                                                <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto"><?php echo e(json_encode($log->new_values, JSON_PRETTY_PRINT)); ?></pre>
                                            </div>
                                        <?php elseif($log->action === 'update'): ?>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <pre class="bg-red-50 p-3 rounded text-sm overflow-x-auto"><?php echo e(json_encode($log->old_values, JSON_PRETTY_PRINT)); ?></pre>
                                                </div>
                                                <div>
                                                    <pre class="bg-green-50 p-3 rounded text-sm overflow-x-auto"><?php echo e(json_encode($log->new_values, JSON_PRETTY_PRINT)); ?></pre>
                                                </div>
                                            </div>
                                        <?php elseif($log->action === 'delete'): ?>
                                            <div>
                                                <pre class="bg-red-50 p-3 rounded text-sm overflow-x-auto"><?php echo e(json_encode($log->old_values, JSON_PRETTY_PRINT)); ?></pre>
                                            </div>
                                        <?php elseif($log->action === 'restore'): ?>
                                            <div>
                                                <pre class="bg-purple-50 p-3 rounded text-sm overflow-x-auto"><?php echo e(json_encode($log->new_values, JSON_PRETTY_PRINT)); ?></pre>
                                            </div>
                                        <?php elseif($log->action === 'force_delete'): ?>
                                            <div>
                                                <pre class="bg-red-50 p-3 rounded text-sm overflow-x-auto"><?php echo e(json_encode($log->old_values, JSON_PRETTY_PRINT)); ?></pre>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                        <div class="text-xs text-gray-500">
                                            <strong>User Agent:</strong> <?php echo e($log->user_agent); ?>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No se encontraron registros de auditoría
                            </td>
                        </tr>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <!--[if BLOCK]><![endif]--><?php if($auditLogs->hasPages()): ?>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <?php echo e($auditLogs->links()); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- ESTADÍSTICAS -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-semibold text-gray-900"><?php echo e($auditLogs->total()); ?></div>
                    <div class="text-gray-500">Total de Registros</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-semibold text-gray-900"><?php echo e($auditLogs->count()); ?></div>
                    <div class="text-gray-500">En Esta Página</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l6-6m0 0v6m0-6h-6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-semibold text-gray-900"><?php echo e(now()->format('d/m/Y H:i')); ?></div>
                    <div class="text-gray-500">Última Actualización</div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--[if BLOCK]><![endif]--><?php if($showExportModal): ?>
<div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-lg shadow-xl">
            
            <button
                type="button"
                wire:click="closeExportModal"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 z-10"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Opciones de Exportación
                </h3>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Selecciona el formato y los campos que deseas incluir en el archivo de exportación.
                </p>

                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Formato de Exportación
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative cursor-pointer" wire:click="$set('exportFormat', 'csv')">
                            <input type="radio" name="exportFormat" value="csv" <?php echo e($exportFormat === 'csv' ? 'checked' : ''); ?> class="sr-only">
                            <div class="p-3 border-2 rounded-lg transition-all duration-200 <?php echo e($exportFormat === 'csv' ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:bg-gray-50'); ?>">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-sm">CSV</div>
                                        <div class="text-xs text-gray-500">Archivo de texto</div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer" wire:click="$set('exportFormat', 'excel')">
                            <input type="radio" name="exportFormat" value="excel" <?php echo e($exportFormat === 'excel' ? 'checked' : ''); ?> class="sr-only">
                            <div class="p-3 border-2 rounded-lg transition-all duration-200 <?php echo e($exportFormat === 'excel' ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:bg-gray-50'); ?>">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-sm">Excel</div>
                                        <div class="text-xs text-gray-500">Hoja de cálculo</div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer" wire:click="$set('exportFormat', 'pdf')">
                            <input type="radio" name="exportFormat" value="pdf" <?php echo e($exportFormat === 'pdf' ? 'checked' : ''); ?> class="sr-only">
                            <div class="p-3 border-2 rounded-lg transition-all duration-200 <?php echo e($exportFormat === 'pdf' ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:bg-gray-50'); ?>">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-sm">PDF</div>
                                        <div class="text-xs text-gray-500">Documento</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Campos a Incluir
                    </label>

                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.fecha" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Fecha</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.usuario" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Usuario</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.accion" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Acción</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.modelo" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Modelo</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.id_registro" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">ID Registro</span>
                            </label>
                        </div>

                        
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.ip_address" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">IP Address</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.valores_anteriores" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Valores Anteriores</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.valores_nuevos" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Valores Nuevos</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.navegador" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Navegador</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model="exportOptions.sistema_operativo" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sistema Operativo</span>
                            </label>
                        </div>
                    </div>
                </div>

                
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button
                        type="button"
                        wire:click="closeExportModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        Cancelar
                    </button>

                    <button
                        type="button"
                        wire:click="exportWithOptions"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="exportWithOptions"
                    >
                        <?php if (isset($component)) { $__componentOriginalf26909af655deaf31c8e20175813a5a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf26909af655deaf31c8e20175813a5a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.spinner','data' => ['size' => 'sm','color' => 'white','wire:loading' => true,'wire:target' => 'exportWithOptions','style' => 'display: none;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','color' => 'white','wire:loading' => true,'wire:target' => 'exportWithOptions','style' => 'display: none;']); ?>
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
                        <span wire:loading.remove wire:target="exportWithOptions">Exportar <?php echo e(ucfirst($exportFormat)); ?></span>
                        <span wire:loading wire:target="exportWithOptions" style="display: none;">Exportando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->


<?php if (isset($component)) { $__componentOriginal115e82920da0ed7c897ee494af74b9d8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal115e82920da0ed7c897ee494af74b9d8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loading-overlay','data' => ['target' => 'exportWithOptions','message' => 'Generando archivo de exportación...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loading-overlay'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'exportWithOptions','message' => 'Generando archivo de exportación...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal115e82920da0ed7c897ee494af74b9d8)): ?>
<?php $attributes = $__attributesOriginal115e82920da0ed7c897ee494af74b9d8; ?>
<?php unset($__attributesOriginal115e82920da0ed7c897ee494af74b9d8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal115e82920da0ed7c897ee494af74b9d8)): ?>
<?php $component = $__componentOriginal115e82920da0ed7c897ee494af74b9d8; ?>
<?php unset($__componentOriginal115e82920da0ed7c897ee494af74b9d8); ?>
<?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/livewire/admin/gestionar-auditoria.blade.php ENDPATH**/ ?>