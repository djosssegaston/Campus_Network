

<?php $__env->startSection('title', 'Modifier Publication'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Modifier Publication</h2>
        <a href="<?php echo e(route('admin.publications.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
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
        <form method="POST" action="<?php echo e(route('admin.publications.update', $publication)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <!-- Contenu -->
            <div class="mb-6">
                <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
                <textarea id="contenu" name="contenu" rows="8" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                          required><?php echo e(old('contenu', $publication->contenu)); ?></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded">
                    Enregistrer
                </button>
                <a href="<?php echo e(route('admin.publications.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <!-- Publication Info -->
    <div class="mt-6 bg-white rounded-lg shadow p-6 max-w-2xl">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Informations de la Publication</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Auteur</p>
                <p class="text-gray-900 font-medium"><?php echo e($publication->utilisateur?->nom ?? 'Unknown'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Réactions</p>
                <p class="text-gray-900 font-medium"><?php echo e($publication->reactions?->count() ?? 0); ?> likes</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Date de création</p>
                <p class="text-gray-900 font-medium"><?php echo e($publication->created_at?->format('d/m/Y H:i') ?? 'N/A'); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Dernière modification</p>
                <p class="text-gray-900 font-medium"><?php echo e($publication->updated_at?->format('d/m/Y H:i') ?? 'N/A'); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\admin\publications\edit.blade.php ENDPATH**/ ?>