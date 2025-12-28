<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'Campus Network'); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="border-b border-gray-100 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex shrink-0 items-center">
                            <a href="/">
                                <h1 class="text-xl font-bold text-blue-600">Campus Network</h1>
                            </a>
                        </div>
                        
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center border-b-2 <?php if(request()->routeIs('dashboard')): ?> border-blue-600 text-gray-900 <?php else: ?> border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 <?php endif; ?> px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">
                                Dashboard
                            </a>
                            <a href="<?php echo e(route('feed.index')); ?>" class="inline-flex items-center border-b-2 <?php if(request()->routeIs('feed.index')): ?> border-blue-600 text-gray-900 <?php else: ?> border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 <?php endif; ?> px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">
                                Fil d'actualités
                            </a>
                            <a href="<?php echo e(route('groupes.index')); ?>" class="inline-flex items-center border-b-2 <?php if(request()->routeIs('groupes.*')): ?> border-blue-600 text-gray-900 <?php else: ?> border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 <?php endif; ?> px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">
                                Groupes
                            </a>
                            <a href="<?php echo e(route('messages.index')); ?>" class="inline-flex items-center border-b-2 <?php if(request()->routeIs('messages.*')): ?> border-blue-600 text-gray-900 <?php else: ?> border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 <?php endif; ?> px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">
                                Messages
                            </a>
                        </div>
                    </div>
                    
                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <div class="relative ms-3">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                    <?php echo e(auth()->user()->name); ?>

                                    
                                    <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                                    <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Profil
                                    </a>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <?php if(isset($header)): ?>
            <header class="bg-white shadow">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>
        
        <!-- Page Content -->
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios.create({
            baseURL: '<?php echo e(url('/')); ?>',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\layouts\authenticated.blade.php ENDPATH**/ ?>