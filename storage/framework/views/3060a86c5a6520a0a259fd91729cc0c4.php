<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'Campus Network'); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-gray-900">
    <!-- Background animation -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse animation-delay-2000"></div>
    </div>
    
    <!-- Content -->
    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Logo -->
        <div class="mb-8 text-center">
            <a href="/" class="inline-flex items-center space-x-2 group">
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-3 group-hover:shadow-xl transition shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <span class="text-3xl font-bold text-white">Campus Network</span>
            </a>
        </div>
        
        <!-- Content Card -->
        <div class="w-full max-w-md">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        
        <!-- Footer Link -->
        <div class="mt-8 text-center text-sm text-gray-400">
            <p>Campus Network © <?php echo e(date('Y')); ?> - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\layouts\auth.blade.php ENDPATH**/ ?>