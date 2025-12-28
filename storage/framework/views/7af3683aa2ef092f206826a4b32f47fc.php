

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tableau de Bord Analytics</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Utilisateurs</h3>
            <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_users']); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Publications</h3>
            <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_publications']); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Groupes</h3>
            <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_groups']); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Utilisateurs Actifs (7j)</h3>
            <p class="text-3xl font-bold text-green-600"><?php echo e($stats['active_users']); ?></p>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Sections Analytics</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?php echo e(route('analytics.users')); ?>" class="block p-4 border rounded-lg hover:bg-blue-50 transition">
                <h3 class="font-bold text-lg mb-2">👥 Utilisateurs</h3>
                <p class="text-gray-600">Statistiques détaillées des utilisateurs</p>
            </a>
            
            <a href="<?php echo e(route('analytics.publications')); ?>" class="block p-4 border rounded-lg hover:bg-blue-50 transition">
                <h3 class="font-bold text-lg mb-2">📝 Publications</h3>
                <p class="text-gray-600">Analyse des publications</p>
            </a>
            
            <a href="<?php echo e(route('analytics.groups')); ?>" class="block p-4 border rounded-lg hover:bg-blue-50 transition">
                <h3 class="font-bold text-lg mb-2">👫 Groupes</h3>
                <p class="text-gray-600">Statistiques des groupes</p>
            </a>
            
            <a href="<?php echo e(route('analytics.engagement')); ?>" class="block p-4 border rounded-lg hover:bg-blue-50 transition">
                <h3 class="font-bold text-lg mb-2">💬 Engagement</h3>
                <p class="text-gray-600">Analyse d'engagement</p>
            </a>
            
            <a href="<?php echo e(route('analytics.export')); ?>" class="block p-4 border rounded-lg hover:bg-blue-50 transition">
                <h3 class="font-bold text-lg mb-2">📊 Exporter</h3>
                <p class="text-gray-600">Exporter les données</p>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\admin\analytics\dashboard.blade.php ENDPATH**/ ?>