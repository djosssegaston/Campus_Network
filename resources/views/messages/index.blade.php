@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
            <a href="{{ route('messages.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-comments mr-2"></i>Conversations
            </a>
        </div>

        @if($conversations->count() > 0)
            <div class="grid grid-cols-4 gap-6 h-[calc(100vh-200px)]">
                <!-- Conversations Sidebar -->
                <div class="col-span-1 bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600">
                        <h3 class="font-semibold text-white">
                            <i class="fas fa-comments mr-2"></i>Conversations
                        </h3>
                    </div>

                    <div class="flex-1 overflow-y-auto divide-y divide-gray-200">
                        @foreach($conversations as $conv)
                            @php
                                $otherUser = $conv->utilisateurs->where('id', '!=', auth()->id())->first();
                            @endphp
                            <a href="{{ route('messages.show', $conv->id) }}" 
                               class="block p-4 hover:bg-blue-50 transition border-l-4 {{ $conversation && $conversation->id == $conv->id ? 'border-blue-600 bg-blue-50' : 'border-transparent' }}">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ substr($otherUser->nom ?? 'C', 0, 2) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">
                                            {{ $otherUser->nom ?? 'Inconnu' }}
                                        </p>
                                        @if($conv->messages->count() > 0)
                                            @php
                                                $lastMessage = $conv->messages->last();
                                            @endphp
                                            <p class="text-xs text-gray-500 truncate">
                                                {{ Str::limit($lastMessage->contenu, 30) }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $lastMessage->created_at->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="col-span-3 bg-white rounded-lg shadow-md flex flex-col overflow-hidden">
                    @if($conversation)
                        <!-- Header -->
                        @php
                            $otherUser = $conversation->utilisateurs->where('id', '!=', auth()->id())->first();
                        @endphp
                        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ substr($otherUser->nom ?? 'C', 0, 2) }}
                                </div>
                                <div class="text-white">
                                    <p class="font-semibold">{{ $otherUser->nom ?? 'Inconnu' }}</p>
                                    <p class="text-xs text-blue-100">Actif maintenant</p>
                                </div>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                            @forelse($conversation->messages as $message)
                                <div class="flex {{ $message->expediteur_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs">
                                        @if($message->expediteur_id !== auth()->id())
                                            <p class="text-xs text-gray-600 ml-3 mb-1">
                                                {{ $message->expediteur->nom }}
                                            </p>
                                        @endif
                                        <div class="flex gap-2 {{ $message->expediteur_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                            <div class="flex-1 {{ $message->expediteur_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }} rounded-lg p-3">
                                                <p class="text-sm break-words">{{ $message->contenu }}</p>
                                                <p class="text-xs {{ $message->expediteur_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
                                                    {{ $message->created_at->format('H:i') }}
                                                    @if($message->expediteur_id === auth()->id() && $message->read_at)
                                                        <i class="fas fa-check-double ml-1"></i>
                                                    @endif
                                                </p>
                                            </div>
                                            @if($message->expediteur_id === auth()->id())
                                                <form action="{{ route('messages.destroy', $message) }}" method="POST" class="opacity-0 hover:opacity-100 transition" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs" onclick="return confirm('Supprimer ce message?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-600">Pas encore de messages</p>
                                    <p class="text-gray-500 text-sm mt-2">Commencez une conversation!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Message Input -->
                        <form method="POST" action="{{ route('messages.store') }}" class="p-4 border-t border-gray-200 bg-white">
                            @csrf
                            <input type="hidden" name="recipient_id" value="{{ $otherUser->id ?? '' }}">
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
                            @error('contenu')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </form>
                    @else
                        <div class="flex-1 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-600 text-lg">Sélectionnez une conversation</p>
                                <p class="text-gray-500 text-sm mt-2">ou démarrez-en une nouvelle</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg mb-6">Aucune conversation pour le moment</p>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
                </a>
            </div>
        @endif
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
@endsection

@section('title', 'Messages')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
            <a href="{{ route('messages.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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

                @if($conversations->count() > 0)
                    <div class="flex-1 overflow-y-auto divide-y divide-gray-200">
                        @foreach($conversations as $conversation)
                            <a href="{{ route('messages.show', $conversation->id) }}" 
                               class="block p-4 hover:bg-blue-50 transition border-l-4 {{ request()->route('conversation') == $conversation->id ? 'border-blue-600 bg-blue-50' : 'border-transparent' }}">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        @php
                                            $otherUser = $conversation->utilisateurs->where('id', '!=', auth()->id())->first();
                                        @endphp
                                        {{ $otherUser ? substr($otherUser->nom, 0, 2) : 'C' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">
                                            {{ $otherUser->nom ?? 'Inconnu' }}
                                        </p>
                                        @if($conversation->messages->count() > 0)
                                            @php
                                                $lastMessage = $conversation->messages->last();
                                            @endphp
                                            <p class="text-xs text-gray-500 truncate">
                                                {{ Str::limit($lastMessage->contenu, 30) }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $lastMessage->created_at->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600 text-sm mb-4">Aucune conversation</p>
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition inline-block">
                                Retour au tableau de bord
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Messages Area -->
            <div class="col-span-3 bg-white rounded-lg shadow-md flex flex-col overflow-hidden">
                @if($conversations->count() > 0 && isset($conversation))
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-sm">
                                @php
                                    $otherUser = $conversation->utilisateurs->where('id', '!=', auth()->id())->first();
                                @endphp
                                {{ substr($otherUser->nom ?? 'C', 0, 2) }}
                            </div>
                            <div class="text-white">
                                <p class="font-semibold">{{ $otherUser->nom ?? 'Inconnu' }}</p>
                                <p class="text-xs text-blue-100">Actif maintenant</p>
                            </div>
                        </div>
                        <a href="#" class="text-white hover:text-blue-100">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </div>

                    <!-- Messages -->
                    <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                        @forelse($conversation->messages as $message)
                            <div class="flex {{ $message->expediteur_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs">
                                    @if($message->expediteur_id !== auth()->id())
                                        <p class="text-xs text-gray-600 ml-3 mb-1">
                                            {{ $message->expediteur->nom }}
                                        </p>
                                    @endif
                                    <div class="flex gap-2 {{ $message->expediteur_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                        <div class="flex-1 {{ $message->expediteur_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }} rounded-lg p-3">
                                            <p class="text-sm break-words">{{ $message->contenu }}</p>
                                            <p class="text-xs {{ $message->expediteur_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
                                                {{ $message->created_at->format('H:i') }}
                                                @if($message->expediteur_id === auth()->id() && $message->read_at)
                                                    <i class="fas fa-check-double ml-1"></i>
                                                @endif
                                            </p>
                                        </div>
                                        @if($message->expediteur_id === auth()->id())
                                            <form action="{{ route('messages.destroy', $message) }}" method="POST" class="opacity-0 hover:opacity-100 transition">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs" onclick="return confirm('Supprimer ce message?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-600">Pas encore de messages</p>
                                <p class="text-gray-500 text-sm mt-2">Commencez une conversation!</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Message Input -->
                    <form method="POST" action="{{ route('messages.store') }}" class="p-4 border-t border-gray-200 bg-white">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $otherUser->id ?? '' }}">
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
                        @error('contenu')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </form>
                @else
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600 text-lg">Sélectionnez une conversation</p>
                            <p class="text-gray-500 text-sm mt-2">ou démarrez-en une nouvelle</p>
                        </div>
                    </div>
                @endif
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
@endsection
