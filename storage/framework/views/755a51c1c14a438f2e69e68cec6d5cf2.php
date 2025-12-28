<!-- Formulaire Commentaire -->
<form action="<?php echo e(route('commentaires.store', $publication)); ?>" method="POST" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
    <?php echo csrf_field(); ?>
    <textarea name="contenu" placeholder="Écrivez un commentaire..." rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
    <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
        <i class="fas fa-comment mr-1"></i>Commenter
    </button>
</form>

<!-- Liste des Commentaires -->
<?php if($publication->commentaires && $publication->commentaires->count() > 0): ?>
    <div class="mt-4 space-y-3">
        <?php $__currentLoopData = $publication->commentaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold text-sm text-gray-900"><?php echo e($comment->utilisateur->nom); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($comment->created_at->diffForHumans()); ?></p>
                    </div>
                    <?php if($comment->utilisateur_id === auth()->id() || auth()->user()->estAdmin()): ?>
                        <form action="<?php echo e(route('commentaires.destroy', $comment)); ?>" method="POST" onsubmit="return confirm('Supprimer ce commentaire?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <p class="text-sm text-gray-700 mt-2"><?php echo e($comment->contenu); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views/partials/commentaires.blade.php ENDPATH**/ ?>