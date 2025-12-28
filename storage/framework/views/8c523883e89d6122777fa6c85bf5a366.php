

<?php $__env->startSection('title', 'Mes Exports de Données'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <a href="<?php echo e(route('profile.edit')); ?>" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au profil
            </a>
            <h1 class="text-4xl font-bold text-gray-900">Mes Exports de Données (RGPD)</h1>
            <p class="text-gray-600 mt-2">Téléchargez une copie de vos données personnelles en vertu de vos droits RGPD</p>
        </div>

        <!-- Messages de statut -->
        <?php if($errors->any()): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-800">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo e($errors->first()); ?>

                </p>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo e(session('success')); ?>

                </p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-800">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo e(session('error')); ?>

                </p>
            </div>
        <?php endif; ?>

        <!-- Section : Créer une nouvelle demande -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-download mr-2 text-blue-600"></i>
                Créer une nouvelle demande d'export
            </h2>
            
            <p class="text-gray-600 mb-6">
                Nous vous enverrons un e-mail avec un lien de téléchargement. Les fichiers restent disponibles pendant 32 jours.
            </p>

            <form method="POST" action="<?php echo e(route('exports.store')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block font-semibold text-gray-900 mb-3">Format d'export</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input 
                                type="radio" 
                                name="format" 
                                value="json"
                                checked
                                class="w-4 h-4 text-blue-600"
                            >
                            <span class="ml-3">
                                <span class="font-semibold text-gray-900">JSON</span>
                                <p class="text-gray-600 text-sm">Format structuré, idéal pour l'informatique</p>
                            </span>
                        </label>

                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input 
                                type="radio" 
                                name="format" 
                                value="csv"
                                class="w-4 h-4 text-blue-600"
                            >
                            <span class="ml-3">
                                <span class="font-semibold text-gray-900">CSV</span>
                                <p class="text-gray-600 text-sm">Lisible dans Excel ou Google Sheets</p>
                            </span>
                        </label>

                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input 
                                type="radio" 
                                name="format" 
                                value="zip"
                                class="w-4 h-4 text-blue-600"
                            >
                            <span class="ml-3">
                                <span class="font-semibold text-gray-900">ZIP</span>
                                <p class="text-gray-600 text-sm">Archive contenant JSON et CSV</p>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Créer l'export
                    </button>
                </div>
            </form>
        </div>

        <!-- Section : Historique des exports -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-history mr-2 text-purple-600"></i>
                Historique de mes exports
            </h2>

            <?php if(count($exports) > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $exports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $export): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-semibold text-gray-900">
                                            Export <?php echo e(strtoupper($export->format)); ?>

                                        </span>
                                        
                                        <?php if($export->status === 'pending'): ?>
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                                <i class="fas fa-clock mr-1"></i>
                                                En attente
                                            </span>
                                        <?php elseif($export->status === 'processing'): ?>
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                <i class="fas fa-spinner fa-spin mr-1"></i>
                                                Traitement en cours
                                            </span>
                                        <?php elseif($export->status === 'completed'): ?>
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Complété
                                            </span>
                                        <?php elseif($export->status === 'failed'): ?>
                                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Erreur
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="text-gray-600 text-sm">
                                        Créé: <?php echo e($export->created_at->format('d/m/Y H:i')); ?>

                                    </p>

                                    <?php if($export->downloaded_at): ?>
                                        <p class="text-gray-600 text-sm">
                                            Téléchargé: <?php echo e($export->downloaded_at->format('d/m/Y H:i')); ?>

                                        </p>
                                    <?php endif; ?>

                                    <?php if($export->expires_at): ?>
                                        <p class="text-gray-600 text-sm">
                                            Expire: <?php echo e($export->expires_at->format('d/m/Y H:i')); ?>

                                            <?php if($export->isExpired()): ?>
                                                <span class="text-red-600 font-semibold">(Expiré)</span>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Progression -->
                                <?php if($export->status === 'processing'): ?>
                                    <div class="text-right">
                                        <div class="w-48 bg-gray-200 rounded-full h-2 mb-2">
                                            <div 
                                                class="bg-blue-600 h-2 rounded-full transition-all"
                                                style="width: <?php echo e($export->getProgress()); ?>%; height: 100%;"
                                            ></div>
                                        </div>
                                        <p class="text-gray-600 text-sm"><?php echo e($export->getProgress()); ?>% (<?php echo e($export->processed_items); ?>/<?php echo e($export->total_items); ?>)</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if($export->error_message): ?>
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded">
                                    <p class="text-red-800 text-sm">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        <?php echo e($export->error_message); ?>

                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="flex gap-3">
                                <?php if($export->isDownloadable()): ?>
                                    <a 
                                        href="<?php echo e(route('exports.download', $export)); ?>"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition"
                                    >
                                        <i class="fas fa-download mr-2"></i>
                                        Télécharger
                                    </a>
                                <?php endif; ?>

                                <?php if($export->status !== 'completed' || $export->isExpired()): ?>
                                    <form 
                                        method="POST" 
                                        action="<?php echo e(route('exports.destroy', $export)); ?>"
                                        style="display: inline;"
                                        onsubmit="return confirm('Êtes-vous sûr ?')"
                                    >
                                        <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                        <button 
                                            type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition"
                                        >
                                            <i class="fas fa-trash mr-2"></i>
                                            Supprimer
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <?php if($exports->hasPages()): ?>
                    <div class="mt-6">
                        <?php echo e($exports->links()); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">Vous n'avez pas encore créé d'export</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Section : Informations RGPD -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-bold text-blue-900 mb-3 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                À propos de vos données
            </h3>
            <ul class="text-blue-800 space-y-2 text-sm">
                <li><i class="fas fa-check mr-2"></i> En vertu du RGPD, vous avez le droit d'accéder à vos données personnelles</li>
                <li><i class="fas fa-check mr-2"></i> Les exports contiennent : profil, publications, commentaires, messages, groupes, et notifications</li>
                <li><i class="fas fa-check mr-2"></i> Les fichiers restent disponibles pendant 32 jours, puis sont automatiquement supprimés</li>
                <li><i class="fas fa-check mr-2"></i> Vous pouvez créer plusieurs exports en différents formats</li>
                <li><i class="fas fa-check mr-2"></i> Vos données sont exportées en clair sans chiffrement</li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\profile\exports.blade.php ENDPATH**/ ?>