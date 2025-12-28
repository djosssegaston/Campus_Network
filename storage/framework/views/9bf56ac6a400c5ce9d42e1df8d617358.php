

<?php $__env->startSection('title', 'Recherche'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Recherche</h1>
        <p class="text-gray-600 mt-2">Trouvez des publications, utilisateurs et groupes</p>
    </div>

    <!-- Barre de recherche -->
    <form method="GET" action="<?php echo e(route('search.index')); ?>" class="mb-8">
        <div class="flex gap-4">
            <input 
                type="text" 
                name="q" 
                value="<?php echo e($query); ?>" 
                placeholder="Rechercher..." 
                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
                minlength="2"
            >
            <select 
                name="type" 
                class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="all" <?php echo e($type === 'all' ? 'selected' : ''); ?>>Tous</option>
                <option value="publication" <?php echo e($type === 'publication' ? 'selected' : ''); ?>>Publications</option>
                <option value="utilisateur" <?php echo e($type === 'utilisateur' ? 'selected' : ''); ?>>Utilisateurs</option>
                <option value="groupe" <?php echo e($type === 'groupe' ? 'selected' : ''); ?>>Groupes</option>
            </select>
            <button 
                type="submit" 
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
                Rechercher
            </button>
        </div>
    </form>

    <?php if(empty($query)): ?>
        <!-- Message d'accueil -->
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-600 text-lg">Entrez un terme de recherche pour commencer</p>
        </div>
    <?php else: ?>
        <!-- Résultats -->
        <?php if($results['publications'] === null && $results['utilisateurs'] === null && $results['groupes'] === null): ?>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600 text-lg">Aucun résultat trouvé</p>
            </div>
        <?php else: ?>
            <!-- Publications -->
            <?php if($results['publications'] !== null && count($results['publications'])): ?>
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-newspaper mr-3 text-blue-600"></i>
                        Publications (<?php echo e($results['publications']->total()); ?>)
                    </h2>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $results['publications']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <a href="<?php echo e(route('publications.show', $publication->id)); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            Auteur: <?php echo e($publication->utilisateur->nom ?? 'Utilisateur supprimé'); ?>

                                        </a>
                                        <p class="text-gray-600 mt-2"><?php echo e(Str::limit($publication->contenu, 150)); ?></p>
                                        <?php if($publication->groupe): ?>
                                            <span class="inline-block mt-2 px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded">
                                                <?php echo e($publication->groupe->nom); ?>

                                            </span>
                                        <?php endif; ?>
                                        <p class="text-gray-400 text-sm mt-3">
                                            <i class="fas fa-clock mr-1"></i>
                                            <?php echo e($publication->created_at->diffForHumans()); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($results['publications']->hasPages()): ?>
                        <div class="mt-6">
                            <?php echo e($results['publications']->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Utilisateurs -->
            <?php if($results['utilisateurs'] !== null && count($results['utilisateurs'])): ?>
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-users mr-3 text-green-600"></i>
                        Utilisateurs (<?php echo e($results['utilisateurs']->total()); ?>)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $results['utilisateurs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $utilisateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                                <div class="flex items-center gap-4 mb-4">
                                    <?php if($utilisateur->avatar_url): ?>
                                        <img 
                                            src="<?php echo e($utilisateur->avatar_url); ?>" 
                                            alt="<?php echo e($utilisateur->nom); ?>"
                                            class="w-12 h-12 rounded-full object-cover"
                                        >
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?php echo e(route('profile.show', $utilisateur->id)); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            <?php echo e($utilisateur->nom); ?>

                                        </a>
                                        <?php if($utilisateur->role): ?>
                                            <p class="text-gray-500 text-sm"><?php echo e($utilisateur->role->nom); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if($utilisateur->filiere): ?>
                                    <p class="text-gray-600 text-sm">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        <?php echo e($utilisateur->filiere); ?>

                                    </p>
                                <?php endif; ?>
                                <p class="text-gray-400 text-xs mt-2"><?php echo e($utilisateur->email); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($results['utilisateurs']->hasPages()): ?>
                        <div class="mt-6">
                            <?php echo e($results['utilisateurs']->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Groupes -->
            <?php if($results['groupes'] !== null && count($results['groupes'])): ?>
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-layer-group mr-3 text-purple-600"></i>
                        Groupes (<?php echo e($results['groupes']->total()); ?>)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php $__currentLoopData = $results['groupes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                                <a href="<?php echo e(route('groupes.show', $groupe->id)); ?>" class="text-blue-600 hover:text-blue-800 text-xl font-semibold">
                                    <?php echo e($groupe->nom); ?>

                                </a>
                                <p class="text-gray-600 mt-2"><?php echo e(Str::limit($groupe->description, 100)); ?></p>
                                <div class="flex gap-6 mt-4 text-gray-600 text-sm">
                                    <span>
                                        <i class="fas fa-users mr-1"></i>
                                        <?php echo e($groupe->utilisateurs_count); ?> membres
                                    </span>
                                    <span>
                                        <i class="fas fa-newspaper mr-1"></i>
                                        <?php echo e($groupe->publications_count); ?> publications
                                    </span>
                                </div>
                                <a 
                                    href="<?php echo e(route('groupes.show', $groupe->id)); ?>" 
                                    class="inline-block mt-4 px-4 py-2 bg-purple-600 text-white text-sm rounded hover:bg-purple-700 transition"
                                >
                                    Voir le groupe
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($results['groupes']->hasPages()): ?>
                        <div class="mt-6">
                            <?php echo e($results['groupes']->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\search\index.blade.php ENDPATH**/ ?>