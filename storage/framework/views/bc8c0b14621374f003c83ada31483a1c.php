<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- COLUMNA 1: DATOS DE CUENTA -->
    <div class="mb-4">
        <!-- FOTO DE PERFIL -->
        <div class="mb-6" x-data="{photoName: null, photoPreview: null}">
            <input type="file" class="hidden"
                        wire:model.live="form.photo"
                        x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto de Perfil</label>

            <div class="flex items-center">
                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <!--[if BLOCK]><![endif]--><?php if($isEditing && $this->form->userModel): ?>
                        <img src="<?php echo e($this->form->userModel->profile_photo_url); ?>" alt="Current Profile Photo" class="rounded-full h-20 w-20 object-cover border-2 border-gray-200 dark:border-gray-600">
                    <?php else: ?>
                        <div class="rounded-full h-20 w-20 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center border-2 border-blue-500"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <div class="ml-4">
                    <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['class' => 'mr-2','type' => 'button','xOn:click.prevent' => '$refs.photo.click()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mr-2','type' => 'button','x-on:click.prevent' => '$refs.photo.click()']); ?>
                        Seleccionar Foto
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
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs block mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>

        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2"><?php echo e(__('users.form.account_data')); ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Usuario -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.username')); ?></label>
                <input type="text" wire:model="form.usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.usuario'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Correo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.email')); ?></label>
                <input type="email" wire:model="form.correo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Rol (Solo Admin puede cambiarlo) -->
            <!--[if BLOCK]><![endif]--><?php if (\Illuminate\Support\Facades\Blade::check('admin')): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.role')); ?></label>
                <select wire:model.live="form.tipo_usuario_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Seleccione un Rol</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tipos_usuario_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tipo->id); ?>"><?php echo e($tipo->rol); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.tipo_usuario_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <?php else: ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.role')); ?></label>
                    <!--[if BLOCK]><![endif]--><?php if(auth()->user()->esEntrenador()): ?>
                        <!-- Si es Entrenador, el rol es fijo: Atleta -->
                        <div class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                            Atleta
                        </div>
                    <?php else: ?>
                        <select wire:model.live="form.tipo_usuario_id" id="tipo_usuario_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccione un Rol</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tipos_usuario_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tipo->id); ?>"><?php echo e($tipo->rol); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.tipo_usuario_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Entrenador Asignado (Solo si es Atleta) -->
            <!--[if BLOCK]><![endif]--><?php if($form->tipo_usuario_id == \App\Enums\UserRole::Atleta->value && !auth()->user()->esEntrenador()): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.trainer')); ?></label>
                <select wire:model="form.entrenador_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value=""><?php echo e(__('users.form.select_trainer')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $entrenadores_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entrenador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($entrenador->id); ?>"><?php echo e($entrenador->nombre_1); ?> <?php echo e($entrenador->apellido_1); ?> (<?php echo e($entrenador->usuario); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.entrenador_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Sección 2: Datos Personales -->
    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2"><?php echo e(__('users.form.personal_data')); ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nombre 1 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.first_name')); ?></label>
                <input type="text" wire:model="form.nombre_1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.nombre_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Nombre 2 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.second_name')); ?></label>
                <input type="text" wire:model="form.nombre_2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.nombre_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Apellido 1 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.last_name')); ?></label>
                <input type="text" wire:model="form.apellido_1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.apellido_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Apellido 2 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.second_last_name')); ?></label>
                <input type="text" wire:model="form.apellido_2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.apellido_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.phone')); ?></label>
                <input type="text" wire:model="form.telefono" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Fecha Nacimiento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('users.form.birthdate')); ?></label>
                <input type="date" wire:model="form.fecha_nacimiento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>

<!--[if BLOCK]><![endif]--><?php if(!$isEditing): ?>
    <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-md">
        <p class="text-sm text-blue-700 dark:text-blue-300">
            <i class="fas fa-info-circle mr-2"></i> <?php echo e(__('users.form.default_password_note')); ?>

        </p>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->


<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/livewire/admin/usuarios-form.blade.php ENDPATH**/ ?>