

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Admin Dashboard</h2>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm font-medium">Total Utilisateurs</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($totalUsers); ?></div>
                    <div class="text-xs text-gray-500 mt-2"><?php echo e($usersThisMonth); ?> ce mois</div>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292m-7.08 2.474A9 9 0 1127.05 10.1m-6.157 6.475L21 21"></path>
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('dashboard')); ?>" class="text-blue-600 hover:text-blue-700 text-sm mt-4 inline-block">Retour →</a>
        </div>

        <!-- Total Publications -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm font-medium">Total Publications</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($totalPublications); ?></div>
                    <div class="text-xs text-gray-500 mt-2"><?php echo e($publicationsThisMonth); ?> ce mois</div>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('publications.index')); ?>" class="text-green-600 hover:text-green-700 text-sm mt-4 inline-block">Retour →</a>
        </div>

        <!-- Total Groupes -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm font-medium">Total Groupes</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($totalGroupes); ?></div>
                    <div class="text-xs text-gray-500 mt-2">Actifs</div>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"></path>
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('groupes.index')); ?>" class="text-purple-600 hover:text-purple-700 text-sm mt-4 inline-block">Retour →</a>
        </div>

        <!-- Total Messages -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm font-medium">Total Messages</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($totalMessages); ?></div>
                    <div class="text-xs text-gray-500 mt-2">Commentaires: <?php echo e($totalComments); ?></div>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('admin.messages.index')); ?>" class="text-yellow-600 hover:text-yellow-700 text-sm mt-4 inline-block">Gérer →</a>
        </div>
    </div>

    <!-- Recent Activities Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Utilisateurs Récents</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between pb-3 border-b last:border-b-0">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900"><?php echo e($user->nom); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($user->email); ?></p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Nouveau
                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-sm">Aucun utilisateur récent</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo e(route('users.index')); ?>" class="text-blue-600 hover:text-blue-700 text-sm mt-4 block">Voir tous →</a>
        </div>

        <!-- Recent Publications -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Publications Récentes</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $recentPublications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="pb-3 border-b last:border-b-0">
                        <p class="text-sm font-medium text-gray-900 truncate"><?php echo e(Str::limit($pub->contenu, 30)); ?></p>
                        <p class="text-xs text-gray-500">par <?php echo e($pub->utilisateur?->nom ?? 'Unknown'); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($pub->created_at?->diffForHumans() ?? 'N/A'); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-sm">Aucune publication récente</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo e(route('publications.index')); ?>" class="text-green-600 hover:text-green-700 text-sm mt-4 block">Voir tous →</a>
        </div>

        <!-- Recent Groupes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Groupes Récents</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $recentGroupes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="pb-3 border-b last:border-b-0">
                        <p class="text-sm font-medium text-gray-900"><?php echo e($groupe->nom); ?></p>
                        <p class="text-xs text-gray-500">Membres: <?php echo e($groupe->utilisateurs?->count() ?? 0); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($groupe->created_at?->diffForHumans() ?? 'N/A'); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-sm">Aucun groupe récent</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo e(route('groupes.index')); ?>" class="text-purple-600 hover:text-purple-700 text-sm mt-4 block">Voir tous →</a>
        </div>
    </div>

    <!-- Partages Statistics -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Statistiques Supplémentaires</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Partages</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e($totalShares); ?></p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C9.839 12.938 11.07 12.5 12.5 12.5c1.43 0 2.661.437 3.816.841.163.058.326.115.489.172a.75.75 0 00.734-1.265c-.147-.042-.294-.087-.441-.133C15.667 11.179 14.166 10.75 12.5 10.75c-1.666 0-3.167.429-4.684.84-.147.046-.294.091-.441.133a.75.75 0 00.734 1.265c.163-.057.326-.114.489-.172z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>