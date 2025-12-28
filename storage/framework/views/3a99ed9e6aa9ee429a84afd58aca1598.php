

<?php $__env->startSection('title', 'Modifier Groupe'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Modifier Groupe</h2>
        <a href="<?php echo e(route('admin.groupes.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
            ← Retour
        </a>
    </div>

    <!-- Errors -->
    <?php if($errors->any()): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="<?php echo e(route('admin.groupes.update', $groupe)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <!-- Nom -->
            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom du Groupe</label>
                <input type="text" id="nom" name="nom" value="<?php echo e(old('nom', $groupe->nom)); ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="5" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo e(old('description', $groupe->description ?? '')); ?></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded">
                    Enregistrer
                </button>
                <a href="<?php echo e(route('admin.groupes.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <!-- Group Info -->
    <div class="mt-6 bg-white rounded-lg shadow p-6 max-w-2xl">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Informations du Groupe</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Admin du groupe</p>
                <p class="text-gray-900 font-medium"><?php echo e($groupe->admin?->nom ?? 'Unknown'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nombre de membres</p>
                <p class="text-gray-900 font-medium"><?php echo e($groupe->utilisateurs?->count() ?? 0); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Date de création</p>
                <p class="text-gray-900 font-medium"><?php echo e($groupe->created_at?->format('d/m/Y H:i') ?? 'N/A'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Dernière modification</p>
                <p class="text-gray-900 font-medium"><?php echo e($groupe->updated_at?->format('d/m/Y H:i') ?? 'N/A'); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\admin\groupes\edit.blade.php ENDPATH**/ ?>