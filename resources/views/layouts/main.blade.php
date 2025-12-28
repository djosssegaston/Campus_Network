<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Campus Network')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Transitions smoothes */
        a, button {
            transition: all 0.3s ease;
        }
        
        /* Focus rings */
        :focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900">
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only">Aller au contenu principal</a>
    
    @guest
        <!-- Guest Layout (Auth pages) -->
        @yield('content')
    @else
        <!-- Authenticated Layout -->
        <div class="flex flex-col min-h-screen">
            <!-- Header/Navigation -->
            @include('components.navigation')
            
            <!-- Main Content -->
            <main id="main-content" class="flex-1">
                @if (isset($header))
                    <div class="bg-white border-b border-gray-200 sticky top-16 z-40">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                            {{ $header }}
                        </div>
                    </div>
                @endif
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            @include('components.footer')
        </div>
    @endguest
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios.create({
            baseURL: '{{ url('/') }}',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
