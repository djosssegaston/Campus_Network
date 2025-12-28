<!-- Footer Component -->
<footer class="bg-gray-900 text-gray-300 border-t border-gray-800 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- About -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white">Campus Network</span>
                </div>
                <p class="text-sm">Plateforme collaborative pour les étudiants. Connectez, partagez et collaborez avec vos camarades.</p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4">Navigation</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo e(route('dashboard')); ?>" class="hover:text-white transition">Tableau de bord</a></li>
                    <li><a href="<?php echo e(route('feed.index')); ?>" class="hover:text-white transition">Fil d'actualités</a></li>
                    <li><a href="<?php echo e(route('groupes.index')); ?>" class="hover:text-white transition">Groupes</a></li>
                    <li><a href="<?php echo e(route('messages.index')); ?>" class="hover:text-white transition">Messages</a></li>
                </ul>
            </div>
            
            <!-- Resources -->
            <div>
                <h4 class="text-white font-semibold mb-4">Ressources</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                    <li><a href="#" class="hover:text-white transition">Guide d'utilisation</a></li>
                    <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition">Support</a></li>
                </ul>
            </div>
            
            <!-- Legal -->
            <div>
                <h4 class="text-white font-semibold mb-4">Légal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Conditions d'utilisation</a></li>
                    <li><a href="#" class="hover:text-white transition">Politique de confidentialité</a></li>
                    <li><a href="#" class="hover:text-white transition">Cookies</a></li>
                    <li><a href="#" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom -->
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-gray-400">
                &copy; <?php echo e(date('Y')); ?> Campus Network. Tous droits réservés.
            </p>
            
            <!-- Social Links -->
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <a href="#" class="p-2 bg-gray-800 rounded-lg text-gray-300 hover:text-white hover:bg-blue-600 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.29 20v-7.21H5.93v-2.94h2.36V7.74c0-2.33 1.43-3.61 3.51-3.61 1 0 1.86.07 2.11.1v2.44h-1.44c-1.14 0-1.36.54-1.36 1.33v1.73h2.72l-.35 2.94h-2.37V20H8.29z"/>
                    </svg>
                </a>
                <a href="#" class="p-2 bg-gray-800 rounded-lg text-gray-300 hover:text-white hover:bg-blue-600 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7"/>
                    </svg>
                </a>
                <a href="#" class="p-2 bg-gray-800 rounded-lg text-gray-300 hover:text-white hover:bg-blue-600 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16.5 7.5a4 4 0 11-8 0 4 4 0 018 0z" fill="#000"/><circle cx="18" cy="6" r="1" fill="#000"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\components\footer.blade.php ENDPATH**/ ?>