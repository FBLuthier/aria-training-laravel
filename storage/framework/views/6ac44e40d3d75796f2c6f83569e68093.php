     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e($showingTrash ? __('users.trash_title') : __('users.management_title')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white"><?php echo e(__('users.title')); ?></h2>
                        <div class="flex space-x-2">
                            <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['wire:click' => 'toggleTrash','loadingTarget' => 'toggleTrash']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'toggleTrash','loadingTarget' => 'toggleTrash']); ?>
                                <?php echo e($showingTrash ? __('users.filters.all') : __('users.filters.trash')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>

                            
                            <!--[if BLOCK]><![endif]--><?php if(!$showingTrash): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\User::class)): ?>
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['wire:click' => 'create','loadingTarget' => 'create']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'create','loadingTarget' => 'create']); ?>
                                <?php echo e(__('users.create_new')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                            <?php endif; ?>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <div class="relative mb-4">
                        <input wire:model.live.debounce.300ms="search" type="text" 
                            placeholder="<?php echo e(__('users.search_placeholder')); ?>" 
                            class="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:border-blue-500">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

    <!-- TABS DE FILTRADO POR ROL (Solo para Admins) -->
    <!--[if BLOCK]><![endif]--><?php if(!auth()->user()->esEntrenador()): ?>
        <?php if (isset($component)) { $__componentOriginal4481d13a4c28f8e9dc55b19f4caa50ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4481d13a4c28f8e9dc55b19f4caa50ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.filter-pills','data' => ['options' => [
                ['label' => __('users.filters.all'), 'value' => null],
                ['label' => __('users.filters.trainers'), 'value' => \App\Enums\UserRole::Entrenador->value],
                ['label' => __('users.filters.athletes'), 'value' => \App\Enums\UserRole::Atleta->value]
            ],'activeValue' => $filtroRol,'action' => 'setFiltroRol']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filter-pills'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                ['label' => __('users.filters.all'), 'value' => null],
                ['label' => __('users.filters.trainers'), 'value' => \App\Enums\UserRole::Entrenador->value],
                ['label' => __('users.filters.athletes'), 'value' => \App\Enums\UserRole::Atleta->value]
            ]),'activeValue' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filtroRol),'action' => 'setFiltroRol']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4481d13a4c28f8e9dc55b19f4caa50ee)): ?>
<?php $attributes = $__attributesOriginal4481d13a4c28f8e9dc55b19f4caa50ee; ?>
<?php unset($__attributesOriginal4481d13a4c28f8e9dc55b19f4caa50ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4481d13a4c28f8e9dc55b19f4caa50ee)): ?>
<?php $component = $__componentOriginal4481d13a4c28f8e9dc55b19f4caa50ee; ?>
<?php unset($__componentOriginal4481d13a4c28f8e9dc55b19f4caa50ee); ?>
<?php endif; ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- TABLA DE USUARIOS -->
    <?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['items' => $this->items]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->items)]); ?>
         <?php $__env->slot('thead', null, []); ?> 
            <tr>
                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'id','currentField' => $sortField,'direction' => $sortDirection->value]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'id','currentField' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection->value)]); ?><?php echo e(__('users.table.id')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'usuario','currentField' => $sortField,'direction' => $sortDirection->value]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'usuario','currentField' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection->value)]); ?><?php echo e(__('users.table.user')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'nombre_1','currentField' => $sortField,'direction' => $sortDirection->value]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'nombre_1','currentField' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection->value)]); ?><?php echo e(__('users.table.name')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    <?php echo e(__('users.table.role')); ?>

                </th>
                <!--[if BLOCK]><![endif]--><?php if($filtroRol === \App\Enums\UserRole::Atleta->value || is_null($filtroRol)): ?>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    <?php echo e(__('users.form.trainer')); ?>

                </th>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    <?php echo e(__('users.table.status')); ?>

                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    <?php echo e(__('users.table.actions')); ?>

                </th>
            </tr>
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('tbody', null, []); ?> 
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $this->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e($user->id); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($user->usuario); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <?php echo e($user->nombre_1); ?> <?php echo e($user->apellido_1); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if (isset($component)) { $__componentOriginalfc2fd5a24fd5d4a415991c564d7993f8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfc2fd5a24fd5d4a415991c564d7993f8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.role-badge','data' => ['role' => $user->tipo_usuario_id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('role-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['role' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->tipo_usuario_id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfc2fd5a24fd5d4a415991c564d7993f8)): ?>
<?php $attributes = $__attributesOriginalfc2fd5a24fd5d4a415991c564d7993f8; ?>
<?php unset($__attributesOriginalfc2fd5a24fd5d4a415991c564d7993f8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfc2fd5a24fd5d4a415991c564d7993f8)): ?>
<?php $component = $__componentOriginalfc2fd5a24fd5d4a415991c564d7993f8; ?>
<?php unset($__componentOriginalfc2fd5a24fd5d4a415991c564d7993f8); ?>
<?php endif; ?>
                    </td>
                    <!--[if BLOCK]><![endif]--><?php if($filtroRol === \App\Enums\UserRole::Atleta->value || is_null($filtroRol)): ?>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <!--[if BLOCK]><![endif]--><?php if($user->tipo_usuario_id === \App\Enums\UserRole::Atleta && $user->entrenador): ?>
                            <?php echo e($user->entrenador->nombre_1); ?> <?php echo e($user->entrenador->apellido_1); ?>

                        <?php elseif($user->tipo_usuario_id === \App\Enums\UserRole::Atleta): ?>
                            <span class="text-yellow-500 italic">Sin asignar</span>
                        <?php else: ?>
                            -
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <!--[if BLOCK]><![endif]--><?php if($user->estado): ?>
                            <span class="text-green-600 font-bold">Activo</span>
                        <?php else: ?>
                            <span class="text-red-600 font-bold">Inactivo</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <!--[if BLOCK]><![endif]--><?php if($showingTrash): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restore', $user)): ?>
                            <button wire:click="restore(<?php echo e($user->id); ?>)" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-3">Restaurar</button>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('forceDelete', $user)): ?>
                            <button wire:click="forceDelete(<?php echo e($user->id); ?>)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <button wire:click="$dispatch('open-audit-history', { modelType: 'App\\Models\\User', modelId: <?php echo e($user->id); ?> })" class="text-gray-400 hover:text-gray-600 mr-2" title="Ver Historial">
                                <i class="fas fa-history"></i>
                            </button>
                            <!--[if BLOCK]><![endif]--><?php if(auth()->user()->esAdmin() && $user->id !== auth()->id()): ?>
                            <a href="<?php echo e(route('admin.impersonate', $user->id)); ?>" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2" title="Iniciar Sesión Como...">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $user)): ?>
                            <button wire:click="confirmPasswordReset(<?php echo e($user->id); ?>)" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2" title="Restablecer Contraseña">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </button>
                            <button wire:click="edit(<?php echo e($user->id); ?>)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Editar</button>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $user)): ?>
                            <button wire:click="delete(<?php echo e($user->id); ?>)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                            <?php endif; ?>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        No se encontraron usuarios.
                    </td>
                </tr>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8463834ba515134d5c98b88e1a9dc03)): ?>
<?php $attributes = $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03; ?>
<?php unset($__attributesOriginalc8463834ba515134d5c98b88e1a9dc03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8463834ba515134d5c98b88e1a9dc03)): ?>
<?php $component = $__componentOriginalc8463834ba515134d5c98b88e1a9dc03; ?>
<?php unset($__componentOriginalc8463834ba515134d5c98b88e1a9dc03); ?>
<?php endif; ?>

    <!-- MODAL DE CREACIÓN / EDICIÓN -->
    <?php if (isset($component)) { $__componentOriginaldf85e6552b95df616d59caebcbf2473d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf85e6552b95df616d59caebcbf2473d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-modal','data' => ['wire:model' => 'showFormModal','show' => $showFormModal,'maxWidth' => '4xl','cancelAction' => 'closeModal','title' => $isEditing ? 'Editar Usuario' : 'Crear Nuevo Usuario','submitText' => $isEditing ? 'Actualizar Usuario' : 'Crear Usuario']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'showFormModal','show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showFormModal),'maxWidth' => '4xl','cancelAction' => 'closeModal','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isEditing ? 'Editar Usuario' : 'Crear Nuevo Usuario'),'submitText' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isEditing ? 'Actualizar Usuario' : 'Crear Usuario')]); ?>
        <!-- MODAL DE FORMULARIO -->
    <?php echo $__env->make('livewire.admin.usuarios-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf85e6552b95df616d59caebcbf2473d)): ?>
<?php $attributes = $__attributesOriginaldf85e6552b95df616d59caebcbf2473d; ?>
<?php unset($__attributesOriginaldf85e6552b95df616d59caebcbf2473d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf85e6552b95df616d59caebcbf2473d)): ?>
<?php $component = $__componentOriginaldf85e6552b95df616d59caebcbf2473d; ?>
<?php unset($__componentOriginaldf85e6552b95df616d59caebcbf2473d); ?>
<?php endif; ?>

    <!-- MODAL DE AUDITORÍA -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('components.audit-history', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-42835593-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    
    <?php if (isset($component)) { $__componentOriginal5b8b2d0f151a30be878e1a760ec3900c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirmation-modal','data' => ['show' => $deletingId !== null,'entangleProperty' => 'deletingId']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deletingId !== null),'entangleProperty' => 'deletingId']); ?>
         <?php $__env->slot('title', null, []); ?> Eliminar Usuario <?php $__env->endSlot(); ?>
         <?php $__env->slot('content', null, []); ?> ¿Estás seguro? Se moverá a la papelera. <?php $__env->endSlot(); ?>
         <?php $__env->slot('footer', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['wire:click' => '$set(\'deletingId\', null)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$set(\'deletingId\', null)']); ?>Cancelar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.danger-button','data' => ['class' => 'ml-3','wire:click' => 'performDelete']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('danger-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ml-3','wire:click' => 'performDelete']); ?>Eliminar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11)): ?>
<?php $attributes = $__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11; ?>
<?php unset($__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11)): ?>
<?php $component = $__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11; ?>
<?php unset($__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c)): ?>
<?php $attributes = $__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c; ?>
<?php unset($__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b8b2d0f151a30be878e1a760ec3900c)): ?>
<?php $component = $__componentOriginal5b8b2d0f151a30be878e1a760ec3900c; ?>
<?php unset($__componentOriginal5b8b2d0f151a30be878e1a760ec3900c); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal5b8b2d0f151a30be878e1a760ec3900c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirmation-modal','data' => ['show' => $restoringId !== null,'entangleProperty' => 'restoringId']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($restoringId !== null),'entangleProperty' => 'restoringId']); ?>
         <?php $__env->slot('title', null, []); ?> Restaurar Usuario <?php $__env->endSlot(); ?>
         <?php $__env->slot('content', null, []); ?> ¿Estás seguro de querer restaurar este usuario? <?php $__env->endSlot(); ?>
         <?php $__env->slot('footer', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['wire:click' => '$set(\'restoringId\', null)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$set(\'restoringId\', null)']); ?>Cancelar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'ml-3','wire:click' => 'performRestore']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ml-3','wire:click' => 'performRestore']); ?>Restaurar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c)): ?>
<?php $attributes = $__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c; ?>
<?php unset($__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b8b2d0f151a30be878e1a760ec3900c)): ?>
<?php $component = $__componentOriginal5b8b2d0f151a30be878e1a760ec3900c; ?>
<?php unset($__componentOriginal5b8b2d0f151a30be878e1a760ec3900c); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal5b8b2d0f151a30be878e1a760ec3900c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirmation-modal','data' => ['show' => $forceDeletingId !== null,'entangleProperty' => 'forceDeletingId']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($forceDeletingId !== null),'entangleProperty' => 'forceDeletingId']); ?>
         <?php $__env->slot('title', null, []); ?> Eliminar Permanentemente <?php $__env->endSlot(); ?>
         <?php $__env->slot('content', null, []); ?> ¿Estás seguro? Esta acción es irreversible y eliminará al usuario definitivamente. <?php $__env->endSlot(); ?>
         <?php $__env->slot('footer', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['wire:click' => '$set(\'forceDeletingId\', null)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$set(\'forceDeletingId\', null)']); ?>Cancelar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.danger-button','data' => ['class' => 'ml-3','wire:click' => 'performForceDelete']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('danger-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ml-3','wire:click' => 'performForceDelete']); ?>Eliminar Permanentemente <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11)): ?>
<?php $attributes = $__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11; ?>
<?php unset($__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11)): ?>
<?php $component = $__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11; ?>
<?php unset($__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c)): ?>
<?php $attributes = $__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c; ?>
<?php unset($__attributesOriginal5b8b2d0f151a30be878e1a760ec3900c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b8b2d0f151a30be878e1a760ec3900c)): ?>
<?php $component = $__componentOriginal5b8b2d0f151a30be878e1a760ec3900c; ?>
<?php unset($__componentOriginal5b8b2d0f151a30be878e1a760ec3900c); ?>
<?php endif; ?>

    
    <!--[if BLOCK]><![endif]--><?php if($resettingPasswordId !== null): ?>
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['show' => true,'maxWidth' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => true,'maxWidth' => 'md']); ?>
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Restablecer Contraseña
            </h3>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nueva Contraseña</label>
                <div class="flex mt-1">
                    <input type="text" wire:model="newPassword" class="flex-1 block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Escribe o genera una contraseña">
                    <button wire:click="generatePassword" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-500">
                        <i class="fas fa-random mr-2"></i> Generar
                    </button>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newPassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Asegúrate de copiar esta contraseña y enviársela al usuario.
                </p>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['wire:click' => '$set(\'resettingPasswordId\', null)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$set(\'resettingPasswordId\', null)']); ?>
                    Cancelar
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['wire:click' => 'performPasswordReset']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'performPasswordReset']); ?>
                    Guardar Cambios
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


</div>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/livewire/admin/gestionar-usuarios.blade.php ENDPATH**/ ?>