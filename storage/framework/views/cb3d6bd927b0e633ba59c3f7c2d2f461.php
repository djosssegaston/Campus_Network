<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Network - Connectez-vous avec vos camarades</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white shadow-md z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-lg flex items-center justify-center font-bold text-white">
                        CN
                    </div>
                    <span class="text-2xl font-bold text-gray-900">Campus Network</span>
                </div>
                <div class="space-x-4">
                    <?php if(Route::has('login')): ?>
                        <a href="<?php echo e(route('login')); ?>" class="px-6 py-2 text-gray-700 hover:text-blue-600 transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                        </a>
                    <?php endif; ?>
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-user-plus mr-2"></i>S'inscrire
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-br from-blue-600 via-blue-500 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-6xl font-bold mb-6">Connectez-vous avec vos camarades</h1>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Campus Network est la plateforme idéale pour tisser des liens, partager des expériences 
                et construire une communauté universitaire dynamique et engagée.
            </p>
            <div class="flex gap-4 justify-center">
                <?php if(Route::has('login')): ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 transition shadow-lg">
                        <i class="fas fa-arrow-right mr-2"></i>Se connecter
                    </a>
                <?php endif; ?>
                <?php if(Route::has('register')): ?>
                    <a href="<?php echo e(route('register')); ?>" class="px-8 py-4 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 transition shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Créer un compte
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-900">Fonctionnalités principales</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-8 shadow-md hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Communauté Active</h3>
                    <p class="text-gray-700">
                        Rejoignez des milliers d'étudiants et connectez-vous avec vos camarades de classe.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-8 shadow-md hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-green-500 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Discussions Enrichissantes</h3>
                    <p class="text-gray-700">
                        Participez à des groupes d'intérêt et engagez-vous dans des conversations significatives.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-8 shadow-md hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-purple-500 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Partage Facile</h3>
                    <p class="text-gray-700">
                        Partagez vos idées, ressources et expériences avec votre communauté universitaire.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-900">Rôles et Permissions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Étudiant -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 ml-3">Étudiant</h3>
                    </div>
                    <ul class="space-y-2 text-gray-700">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Créer des publications</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Rejoindre des groupes</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Commenter et réagir</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Envoyer des messages</li>
                    </ul>
                </div>

                <!-- Modérateur -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-orange-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 ml-3">Modérateur</h3>
                    </div>
                    <ul class="space-y-2 text-gray-700">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Toutes les permissions étudiant</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Modérer le contenu</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Gérer les utilisateurs</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Voir les rapports</li>
                    </ul>
                </div>

                <!-- Admin -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-crown text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 ml-3">Administrateur</h3>
                    </div>
                    <ul class="space-y-2 text-gray-700">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Accès complet système</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Gérer les rôles & permissions</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Voir les analytics</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Maintenance système</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-white text-center">
                <div>
                    <p class="text-5xl font-bold mb-2">1,247</p>
                    <p class="text-lg text-blue-100">Utilisateurs Actifs</p>
                </div>
                <div>
                    <p class="text-5xl font-bold mb-2">89</p>
                    <p class="text-lg text-blue-100">Groupes</p>
                </div>
                <div>
                    <p class="text-5xl font-bold mb-2">3.5K</p>
                    <p class="text-lg text-blue-100">Publications</p>
                </div>
                <div>
                    <p class="text-5xl font-bold mb-2">98%</p>
                    <p class="text-lg text-blue-100">Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Prêt à rejoindre Campus Network?</h2>
            <p class="text-xl text-gray-700 mb-8">
                Créez un compte aujourd'hui et commencez à vous connecter avec votre communauté universitaire.
            </p>
            <div class="flex gap-4 justify-center">
                <?php if(Route::has('login')): ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-8 py-4 border-2 border-blue-600 text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </a>
                <?php endif; ?>
                <?php if(Route::has('register')): ?>
                    <a href="<?php echo e(route('register')); ?>" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-user-plus mr-2"></i>Créer un compte
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-400">&copy; 2025 Campus Network. Tous droits réservés.</p>
                <div class="flex justify-center gap-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-facebook fa-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\welcome.blade.php ENDPATH**/ ?>