

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tableau de Bord de Modération</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Signalements en Attente</h3>
            <p class="text-3xl font-bold text-orange-600"><?php echo e($pendingReports); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total des Signalements</h3>
            <p class="text-3xl font-bold text-gray-900"><?php echo e($totalReports); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Contenus Signalés</h3>
            <p class="text-3xl font-bold text-red-600"><?php echo e($flaggedContent); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Utilisateurs Bannîs</h3>
            <p class="text-3xl font-bold text-gray-900"><?php echo e($bannedUsers); ?></p>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Actions de Modération</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="<?php echo e(route('moderation.reports')); ?>" class="block p-4 border rounded-lg hover:bg-blue-50 transition">
                <h3 class="font-bold text-lg mb-2">📋 Signalements</h3>
                <p class="text-gray-600">Gérer les signalements utilisateurs</p>
            </a>
            
            <a href="<?php echo e(route('moderation.flagged')); ?>" class="block p-4 border rounded-lg hover:bg-blue-50 transition">
                <h3 class="font-bold text-lg mb-2">🚩 Contenus Signalés</h3>
                <p class="text-gray-600">Examiner les contenus signalés</p>
            </a>
            
            <a href="<?php echo e(route('moderation.banned-users')); ?>" class="block p-4 border rounded-lg hover:bg-blue-50 transition">
                <h3 class="font-bold text-lg mb-2">⛔ Utilisateurs Bannîs</h3>
                <p class="text-gray-600">Gérer les utilisateurs bannîs</p>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\admin\moderation\dashboard.blade.php ENDPATH**/ ?>