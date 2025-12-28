

<?php $__env->startSection('title', 'Profil - Campus Network'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mon Profil</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations Personnelles -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-6">Informations Personnelles</h2>
                
                <?php if($errors->has('profile')): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <?php echo e($errors->first('profile')); ?>

                    </div>
                <?php endif; ?>
                
                <form action="<?php echo e(route('profile.update')); ?>" method="POST" class="space-y-4">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name"
                            value="<?php echo e(old('name', auth()->user()->name)); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email"
                            value="<?php echo e(old('email', auth()->user()->email)); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Mettre à jour
                    </button>
                </form>
            </div>
            
            <!-- Changer le Mot de Passe -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-6">Changer le Mot de Passe</h2>
                
                <?php if($errors->has('password')): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <?php echo e($errors->first('password')); ?>

                    </div>
                <?php endif; ?>
                
                <form action="<?php echo e(route('profile.update')); ?>" method="POST" class="space-y-4">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Mot de Passe Actuel</label>
                        <input 
                            id="current_password" 
                            type="password" 
                            name="current_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        >
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau Mot de Passe</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        >
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmez le Mot de Passe</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Changer le Mot de Passe
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Paramètres de Confidentialité -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-lock mr-2 text-blue-600"></i>
                Paramètres de Confidentialité
            </h2>
            <p class="text-gray-600 mb-4">Contrôlez qui peut voir vos informations et vos activités</p>
            <a 
                href="<?php echo e(route('privacy-settings.index')); ?>" 
                class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
                <i class="fas fa-cog mr-2"></i>
                Gérer mes paramètres de confidentialité
            </a>
        </div>

        <!-- Mes Données (RGPD) -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-download mr-2 text-green-600"></i>
                Mes Données (RGPD)
            </h2>
            <p class="text-gray-600 mb-4">Téléchargez une copie de vos données personnelles en vertu de vos droits RGPD</p>
            <a 
                href="<?php echo e(route('exports.index')); ?>" 
                class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
            >
                <i class="fas fa-files mr-2"></i>
                Gérer mes exports
            </a>
        </div>
        
        <!-- Suppression du Compte -->
        <div class="mt-6 bg-red-50 rounded-lg shadow p-6 border border-red-200">
            <h2 class="text-xl font-bold text-red-900 mb-4">Zone de Danger</h2>
            
            <form action="<?php echo e(route('profile.destroy')); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.');">
                <?php echo method_field('DELETE'); ?>
                <?php echo csrf_field(); ?>
                
                <p class="text-red-700 mb-4">Une fois supprimé, votre compte ne peut pas être récupéré.</p>
                
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Supprimer mon compte
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.authenticated', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\profile\edit.blade.php ENDPATH**/ ?>