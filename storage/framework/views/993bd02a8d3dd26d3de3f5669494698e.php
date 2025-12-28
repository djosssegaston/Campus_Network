

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tableau de Bord Maintenance</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">État du Système</h3>
            <div class="space-y-2">
                <?php $__currentLoopData = $health; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $component => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between items-center">
                        <span class="capitalize"><?php echo e($component); ?>:</span>
                        <span class="px-2 py-1 rounded text-sm <?php echo e($status['status'] == 'OK' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($status['status']); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Informations Système</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>PHP:</span>
                    <span class="font-mono"><?php echo e($systemInfo['php_version']); ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Laravel:</span>
                    <span class="font-mono"><?php echo e($systemInfo['laravel_version']); ?></span>
                </div>
                <div class="flex justify-between">
                    <span>BD:</span>
                    <span class="font-mono"><?php echo e($systemInfo['database_size']); ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Stockage:</span>
                    <span class="font-mono"><?php echo e($systemInfo['storage_usage']); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Outils de Maintenance</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST" action="<?php echo e(route('maintenance.cache')); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600">
                    🔄 Optimiser le Cache
                </button>
            </form>
            
            <form method="POST" action="<?php echo e(route('maintenance.migrate')); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600">
                    🔧 Exécuter les Migrations
                </button>
            </form>
            
            <form method="POST" action="<?php echo e(route('maintenance.optimize-db')); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600"
                        onclick="return confirm('Êtes-vous sûr?')">
                    ⚡ Optimiser la BD
                </button>
            </form>
            
            <form method="POST" action="<?php echo e(route('maintenance.cleanup-inactive')); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-orange-500 text-white px-4 py-3 rounded-lg hover:bg-orange-600"
                        onclick="return confirm('Êtes-vous sûr?')">
                    🗑️ Nettoyer Comptes Inactifs
                </button>
            </form>
            
            <form method="POST" action="<?php echo e(route('maintenance.cleanup-files')); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-orange-500 text-white px-4 py-3 rounded-lg hover:bg-orange-600"
                        onclick="return confirm('Êtes-vous sûr?')">
                    📁 Nettoyer Fichiers
                </button>
            </form>
            
            <a href="<?php echo e(route('maintenance.report')); ?>" class="block bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 text-center">
                📊 Générer un Rapport
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\admin\maintenance\dashboard.blade.php ENDPATH**/ ?>