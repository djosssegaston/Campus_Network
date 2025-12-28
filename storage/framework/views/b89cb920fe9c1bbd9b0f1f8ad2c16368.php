

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Rôles</h1>
    
    <div class="mb-6">
        <a href="<?php echo e(route('roles.create')); ?>" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
            + Nouveau Rôle
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Nom</th>
                        <th class="px-4 py-2 text-left">Slug</th>
                        <th class="px-4 py-2 text-left">Permissions</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo e($role->nom); ?></td>
                            <td class="px-4 py-2"><?php echo e($role->slug); ?></td>
                            <td class="px-4 py-2">
                                <span class="bg-gray-100 px-2 py-1 rounded text-sm">
                                    <?php echo e($role->permissions->count()); ?> permissions
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <a href="<?php echo e(route('roles.edit', $role)); ?>" class="text-blue-500 hover:text-blue-700 mr-2">Éditer</a>
                                <form method="POST" action="<?php echo e(route('roles.destroy', $role)); ?>" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center">Aucun rôle trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            <?php echo e($roles->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\admin\roles\index.blade.php ENDPATH**/ ?>