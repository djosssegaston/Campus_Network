

<?php $__env->startSection('title', 'Conversation avec ' . $conversation->utilisateurs->where('id', '!=', auth()->id())->first()->nom ?? 'Inconnu'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="<?php echo e(route('messages.index')); ?>" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Retour aux messages
        </a>

        <!-- Messages Container -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-96">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center font-bold text-sm">
                        <?php
                            $otherUser = $conversation->utilisateurs->where('id', '!=', auth()->id())->first();
                        ?>
                        <?php echo e(substr($otherUser->nom ?? 'C', 0, 2)); ?>

                    </div>
                    <div class="text-white">
                        <p class="font-semibold"><?php echo e($otherUser->nom ?? 'Inconnu'); ?></p>
                        <p class="text-xs text-blue-100">
                            <i class="fas fa-circle text-green-400 mr-1"></i>Actif maintenant
                        </p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $conversation->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex <?php echo e($message->expediteur_id === auth()->id() ? 'justify-end' : 'justify-start'); ?>">
                        <div class="max-w-xs">
                            <?php if($message->expediteur_id !== auth()->id()): ?>
                                <p class="text-xs text-gray-600 ml-3 mb-1">
                                    <?php echo e($message->expediteur->nom); ?>

                                </p>
                            <?php endif; ?>
                            <div class="flex gap-2 <?php echo e($message->expediteur_id === auth()->id() ? 'flex-row-reverse' : ''); ?>">
                                <div class="flex-1 <?php echo e($message->expediteur_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900'); ?> rounded-lg p-3">
                                    <p class="text-sm break-words"><?php echo e($message->contenu); ?></p>
                                    <p class="text-xs <?php echo e($message->expediteur_id === auth()->id() ? 'text-blue-100' : 'text-gray-500'); ?> mt-1">
                                        <?php echo e($message->created_at->format('H:i')); ?>

                                        <?php if($message->expediteur_id === auth()->id() && $message->read_at): ?>
                                            <i class="fas fa-check-double ml-1"></i>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <?php if($message->expediteur_id === auth()->id()): ?>
                                    <form action="<?php echo e(route('messages.destroy', $message)); ?>" method="POST" class="opacity-0 hover:opacity-100 transition">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs" onclick="return confirm('Supprimer ce message?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Pas encore de messages</p>
                        <p class="text-gray-500 text-sm mt-2">Commencez une conversation!</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Message Input -->
            <?php if($otherUser && $otherUser->id): ?>
                <form method="POST" action="<?php echo e(route('messages.store')); ?>" class="p-4 border-t border-gray-200 bg-white">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="recipient_id" value="<?php echo e($otherUser->id); ?>">
                    
                    <?php if($errors->any()): ?>
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="text-sm"><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="flex gap-3">
                        <textarea 
                            name="contenu" 
                            placeholder="Tapez votre message..." 
                            rows="1"
                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none <?php echo e($errors->has('contenu') ? 'border-red-500' : ''); ?>"
                            required></textarea>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="p-4 border-t border-gray-200 bg-red-50 text-red-700 text-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Erreur: Impossible de charger le destinataire. La conversation peut être corrupted.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // Auto-scroll au dernier message
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\messages\show.blade.php ENDPATH**/ ?>