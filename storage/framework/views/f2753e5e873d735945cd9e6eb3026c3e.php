<div>
    <!--[if BLOCK]><![endif]--><?php if($isOpen): ?>
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="close"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Historial de Cambios
                            </h3>
                            <div class="mt-4 max-h-96 overflow-y-auto">
                                <!--[if BLOCK]><![endif]--><?php if(count($logs) > 0): ?>
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acción</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detalles</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo e(\Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i')); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                <?php echo e($log->user_name); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php echo e($log->action === 'created' ? 'bg-green-100 text-green-800' : ''); ?>

                                                    <?php echo e($log->action === 'updated' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                                    <?php echo e($log->action === 'deleted' ? 'bg-red-100 text-red-800' : ''); ?>">
                                                    <?php echo e(ucfirst($log->action)); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                <div class="max-w-xs overflow-hidden text-ellipsis" title="<?php echo e($log->details); ?>">
                                                    <?php echo e(Str::limit($log->details, 30)); ?>

                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </tbody>
                                </table>
                                <?php else: ?>
                                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No hay registros de auditoría para este elemento.</p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="close" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/livewire/components/audit-history.blade.php ENDPATH**/ ?>