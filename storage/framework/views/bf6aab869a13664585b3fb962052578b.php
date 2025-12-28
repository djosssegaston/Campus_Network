<!-- DASHBOARD ÉTUDIANT -->
<div class="space-y-6">
    <!-- Stats Utilisateur -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm uppercase font-semibold">Mes Publications</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">5</p>
                </div>
                <i class="fas fa-pen text-3xl text-blue-200"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm uppercase font-semibold">Mes Groupes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">3</p>
                </div>
                <i class="fas fa-users text-3xl text-green-200"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm uppercase font-semibold">Messages</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">12</p>
                </div>
                <i class="fas fa-envelope text-3xl text-purple-200"></i>
            </div>
        </div>
    </div>

    <!-- Actions Disponibles -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Actions disponibles</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="<?php echo e(route('publications.create')); ?>" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-pen text-blue-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-900">Créer une publication</p>
                    <p class="text-sm text-gray-600">Partager une mise à jour</p>
                </div>
            </a>
            <a href="<?php echo e(route('groupes.index')); ?>" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <i class="fas fa-users text-green-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-900">Découvrir des groupes</p>
                    <p class="text-sm text-gray-600">Rejoindre une communauté</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Feed -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Mon Feed</h3>
        <p class="text-gray-600 text-center py-8">Aucune publication pour le moment</p>
    </div>
</div>
<?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\dashboard-components\student.blade.php ENDPATH**/ ?>