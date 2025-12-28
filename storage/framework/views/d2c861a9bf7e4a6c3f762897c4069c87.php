

<?php $__env->startSection('title', 'Messages'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
            <a href="<?php echo e(route('messages.index')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-comments mr-2"></i>Conversations
            </a>
        </div>

        <?php if($conversations->count() > 0): ?>
            <div class="grid grid-cols-4 gap-6 h-[calc(100vh-200px)]">
                <!-- Conversations Sidebar -->
                <div class="col-span-1 bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600">
                        <h3 class="font-semibold text-white">
                            <i class="fas fa-comments mr-2"></i>Conversations
                        </h3>
                    </div>

                    <div class="flex-1 overflow-y-auto divide-y divide-gray-200">
                        <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $otherUser = $conv->utilisateurs->where('id', '!=', auth()->id())->first();
                            ?>
                            <a href="<?php echo e(route('messages.show', $conv->id)); ?>" 
                               class="block p-4 hover:bg-blue-50 transition border-l-4 <?php echo e($conversation && $conversation->id == $conv->id ? 'border-blue-600 bg-blue-50' : 'border-transparent'); ?>">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        <?php echo e(substr($otherUser->nom ?? 'C', 0, 2)); ?>

                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">
                                            <?php echo e($otherUser->nom ?? 'Inconnu'); ?>

                                        </p>
                                        <?php if($conv->messages->count() > 0): ?>
                                            <?php
                                                $lastMessage = $conv->messages->last();
                                            ?>
                                            <p class="text-xs text-gray-500 truncate">
                                                <?php echo e(Str::limit($lastMessage->contenu, 30)); ?>

                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                <?php echo e($lastMessage->created_at->diffForHumans()); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="col-span-3 bg-white rounded-lg shadow-md flex flex-col overflow-hidden">
                    <?php if($conversation): ?>
                        <!-- Header -->
                        <?php
                            $otherUser = $conversation->utilisateurs->where('id', '!=', auth()->id())->first();
                        ?>
                        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-sm">
                                    <?php echo e(substr($otherUser->nom ?? 'C', 0, 2)); ?>

                                </div>
                                <div class="text-white">
                                    <p class="font-semibold"><?php echo e($otherUser->nom ?? 'Inconnu'); ?></p>
                                    <p class="text-xs text-blue-100">Actif maintenant</p>
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
                                                <form action="<?php echo e(route('messages.destroy', $message)); ?>" method="POST" class="opacity-0 hover:opacity-100 transition" style="display:inline;">
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
                        <form method="POST" action="<?php echo e(route('messages.store')); ?>" class="p-4 border-t border-gray-200 bg-white">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="recipient_id" value="<?php echo e($otherUser->id ?? ''); ?>">
                            <div class="flex gap-3">
                                <textarea 
                                    name="contenu" 
                                    placeholder="Tapez votre message..." 
                                    rows="1"
                                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                    required></textarea>
                                <button 
                                    type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['contenu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-2"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </form>
                    <?php else: ?>
                        <div class="flex-1 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-600 text-lg">Sélectionnez une conversation</p>
                                <p class="text-gray-500 text-sm mt-2">ou démarrez-en une nouvelle</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg mb-6">Aucune conversation pour le moment</p>
                <a href="<?php echo e(route('dashboard')); ?>" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
                </a>
            </div>
        <?php endif; ?>
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

<?php $__env->startSection('title', 'Messages'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
            <a href="<?php echo e(route('messages.index')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-comments mr-2"></i>Conversations
            </a>
        </div>

        <div class="grid grid-cols-4 gap-6 h-screen">
            <!-- Conversations Sidebar -->
            <div class="col-span-1 bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600">
                    <h3 class="font-semibold text-white">
                        <i class="fas fa-comments mr-2"></i>Conversations
                    </h3>
                </div>

                <?php if($conversations->count() > 0): ?>
                    <div class="flex-1 overflow-y-auto divide-y divide-gray-200">
                        <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('messages.show', $conversation->id)); ?>" 
                               class="block p-4 hover:bg-blue-50 transition border-l-4 <?php echo e(request()->route('conversation') == $conversation->id ? 'border-blue-600 bg-blue-50' : 'border-transparent'); ?>">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        <?php
                                            $otherUser = $conversation->utilisateurs->where('id', '!=', auth()->id())->first();
                                        ?>
                                        <?php echo e($otherUser ? substr($otherUser->nom, 0, 2) : 'C'); ?>

                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">
                                            <?php echo e($otherUser->nom ?? 'Inconnu'); ?>

                                        </p>
                                        <?php if($conversation->messages->count() > 0): ?>
                                            <?php
                                                $lastMessage = $conversation->messages->last();
                                            ?>
                                            <p class="text-xs text-gray-500 truncate">
                                                <?php echo e(Str::limit($lastMessage->contenu, 30)); ?>

                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                <?php echo e($lastMessage->created_at->diffForHumans()); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600 text-sm mb-4">Aucune conversation</p>
                            <a href="<?php echo e(route('dashboard')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition inline-block">
                                Retour au tableau de bord
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Messages Area -->
            <div class="col-span-3 bg-white rounded-lg shadow-md flex flex-col overflow-hidden">
                <?php if($conversations->count() > 0 && isset($conversation)): ?>
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-sm">
                                <?php
                                    $otherUser = $conversation->utilisateurs->where('id', '!=', auth()->id())->first();
                                ?>
                                <?php echo e(substr($otherUser->nom ?? 'C', 0, 2)); ?>

                            </div>
                            <div class="text-white">
                                <p class="font-semibold"><?php echo e($otherUser->nom ?? 'Inconnu'); ?></p>
                                <p class="text-xs text-blue-100">Actif maintenant</p>
                            </div>
                        </div>
                        <a href="#" class="text-white hover:text-blue-100">
                            <i class="fas fa-info-circle"></i>
                        </a>
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
                    <form method="POST" action="<?php echo e(route('messages.store')); ?>" class="p-4 border-t border-gray-200 bg-white">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="recipient_id" value="<?php echo e($otherUser->id ?? ''); ?>">
                        <div class="flex gap-3">
                            <textarea 
                                name="contenu" 
                                placeholder="Tapez votre message..." 
                                rows="1"
                                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                required></textarea>
                            <button 
                                type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['contenu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-2"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </form>
                <?php else: ?>
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600 text-lg">Sélectionnez une conversation</p>
                            <p class="text-gray-500 text-sm mt-2">ou démarrez-en une nouvelle</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views/messages/index.blade.php ENDPATH**/ ?>