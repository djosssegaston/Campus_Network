<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Campus Network') }} - @yield('title', 'Home')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @auth
            <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-2xl fixed h-screen overflow-y-auto">
                @php 
                    $isAdmin = auth()->user()->estAdmin();
                    $roleSlug = auth()->user()->role?->slug ?? null;
                @endphp

                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-700 sticky top-0 bg-gray-900">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400">Menu</h3>
                </div>

                <!-- Navigation -->
                <nav class="p-4 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 hover:translate-x-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        <span>Tableau de Bord</span>
                    </a>

                    <!-- Section Étudiant -->
                    @if($roleSlug === 'etudiant')
                        <div class="mt-6 pt-4 border-t border-gray-700">
                            <p class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Espace Étudiant</p>
                            <a href="{{ route('feed.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-blue-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Feed</span>
                            </a>
                            <a href="{{ route('groupes.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-blue-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zM5 20h16a1 1 0 001-1V9a1 1 0 00-1-1H5a1 1 0 00-1 1v10a1 1 0 001 1z"/>
                                </svg>
                                <span>Groupes</span>
                            </a>
                            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-blue-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span>Notifications</span>
                            </a>
                        </div>
                    @endif

                    <!-- Section Admin -->
                    @if($isAdmin)
                        <div class="mt-6 pt-4 border-t border-gray-700">
                            <p class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Administration</p>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-purple-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Panneau Admin</span>
                            </a>
                            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-purple-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3.545M3.545 21H21m-16.5 0h16"/>
                                </svg>
                                <span>Utilisateurs</span>
                            </a>
                            <a href="{{ route('roles.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-purple-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Rôles & Permissions</span>
                            </a>
                            <a href="{{ route('moderation.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-purple-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M4.22 4.22a10 10 0 1114.56 0M4.22 4.22l14.56 14.56"/>
                                </svg>
                                <span>Modération</span>
                            </a>
                            <a href="{{ route('analytics.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-purple-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span>Analytics</span>
                            </a>
                            <a href="{{ route('maintenance.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-purple-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Maintenance</span>
                            </a>
                            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-purple-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                                <span>Paramètres</span>
                            </a>
                        </div>
                    @endif

                    <!-- Section Account -->
                    <div class="mt-6 pt-4 border-t border-gray-700">
                        <p class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Compte</p>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Profil</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-red-700/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>
        @endauth

        <!-- Main Content -->
        <main class="@auth ml-64 @endauth flex-1">
            <!-- Top Bar -->
            <div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent">Campus Network</span>
                        </div>

                        @auth
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->nom }}</span>
                                <div class="px-3 py-1 rounded-full text-xs font-semibold"
                                    @php
                                        $roleColor = [
                                            'etudiant' => 'bg-blue-100 text-blue-700',
                                            'modérateur_groupe' => 'bg-orange-100 text-orange-700',
                                            'admin_groupe' => 'bg-purple-100 text-purple-700',
                                            'modérateur_global' => 'bg-red-100 text-red-700',
                                            'administrateur' => 'bg-green-100 text-green-700',
                                            'super_admin' => 'bg-indigo-100 text-indigo-700',
                                        ];
                                        echo 'class="' . ($roleColor[auth()->user()->role->slug] ?? 'bg-gray-100 text-gray-700') . '"';
                                    @endphp>
                                    {{ \App\Helpers\PermissionHelper::getRoleDisplayName(auth()->user()->role) }}
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Alerts -->
                @if (session('status'))
                    <div class="alert alert-success mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-6">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Content -->
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
