

<?php $__env->startSection('title', 'Groupes'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Groupes</h2>
        <a href="<?php echo e(route('groupes.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Créer Groupe
        </a>
    </div>

    <?php if($groupes->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $groupes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-24"></div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($groupe->nom); ?></h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            <?php echo e($groupe->description ?? 'Pas de description'); ?>

                        </p>
                        
                        <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                            <span><i class="fas fa-users mr-1"></i><?php echo e($groupe->utilisateurs->count()); ?> membres</span>
                            <span class="px-2 py-1 bg-<?php echo e($groupe->visibilite === 'public' ? 'green' : 'gray'); ?>-100 text-<?php echo e($groupe->visibilite === 'public' ? 'green' : 'gray'); ?>-700 rounded text-xs">
                                <?php echo e($groupe->visibilite === 'public' ? 'Public' : 'Privé'); ?>

                            </span>
                        </div>
                        
                        <a href="<?php echo e(route('groupes.show', $groupe->id)); ?>" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center text-sm font-medium block">
                            Voir le groupe
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <?php if($groupes->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($groupes->links()); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 text-lg mb-6">Aucun groupe pour le moment</p>
            <a href="<?php echo e(route('groupes.create')); ?>" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-block">
                <i class="fas fa-plus mr-2"></i>Créer le premier groupe
            </a>
        </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\groupes\index.blade.php ENDPATH**/ ?>