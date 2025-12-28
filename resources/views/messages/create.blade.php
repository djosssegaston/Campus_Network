@extends('layouts.app')

@section('title', 'Utilisateurs - Nouvelle Conversation')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('messages.index') }}" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Retour aux messages
        </a>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Démarrer une conversation</h1>
            <p class="text-gray-600 mt-2">Sélectionnez un utilisateur pour commencer à discuter</p>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <input 
                type="text" 
                id="search-users"
                placeholder="Rechercher un utilisateur..." 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <!-- Users Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="users-grid">
            @forelse($utilisateurs as $user)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition user-card" data-user-name="{{ strtolower($user->nom) }}" data-user-email="{{ strtolower($user->email) }}">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                            {{ substr($user->nom, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $user->nom }}</h3>
                            <p class="text-sm text-gray-600">
                                @if($user->role)
                                    {{ ucfirst($user->role->nom) }}
                                @else
                                    Utilisateur
                                @endif
                            </p>
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm mb-4">
                        {{ $user->email }}
                    </p>

                    <!-- Check if conversation already exists -->
                    @php
                        // Use pre-loaded conversation map from controller (no query!)
                        $existingConversationId = $conversationMap[$user->id] ?? null;
                    @endphp

                    @if($existingConversationId)
                        <a href="{{ route('messages.show', $existingConversationId) }}" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center block">
                            <i class="fas fa-comments mr-2"></i>Continuer la conversation
                        </a>
                    @else
                        <form action="{{ route('messages.create', $user) }}" method="POST" class="w-full start-conversation-form" data-user-id="{{ $user->id }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition start-conversation-btn" data-user-id="{{ $user->id }}">
                                <i class="fas fa-comment-dots mr-2"></i><span class="btn-text">Démarrer une conversation</span>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg">Aucun utilisateur trouvé</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($utilisateurs->hasPages())
            <div class="mt-8">
                {{ $utilisateurs->links() }}
            </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // Recherche en temps réel
    const searchInput = document.getElementById('search-users');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.user-card').forEach(card => {
                const name = card.getAttribute('data-user-name');
                const email = card.getAttribute('data-user-email');
                card.parentElement.style.display = name.includes(searchTerm) || email.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Gérer les soumissions de formulaires de conversation
    document.querySelectorAll('.start-conversation-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const userId = this.getAttribute('data-user-id');
            const btn = this.querySelector(`[data-user-id="${userId}"]`);
            const btnText = btn.querySelector('.btn-text');
            
            // Disable button to prevent double submission
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            
            // Show loading state
            const originalText = btnText.textContent;
            btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Création...';
            
            // If form doesn't submit within 30 seconds, re-enable
            const timeout = setTimeout(() => {
                if (!btn.disabled) return;
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btnText.textContent = originalText;
            }, 30000);
            
            // Listen for response
            const originalOnload = window.onload;
            window.addEventListener('beforeunload', () => {
                clearTimeout(timeout);
            });
        });
    });
</script>
@endsection
