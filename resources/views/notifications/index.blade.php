@extends('layouts.app')

@section('title', 'Notifications - Campus Network')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Notifications</h1>
                <p class="text-gray-600">Vous avez {{ $notifications->total() }} notification(s)</p>
            </div>
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        <i class="fas fa-check mr-2"></i>Marquer tout comme lu
                    </button>
                </form>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifications Container -->
        <div class="bg-white rounded-lg shadow">
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <div class="p-6 hover:bg-gray-50 transition {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            @if($notification->type === 'publication_partagee')
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-share text-green-500"></i>
                                                    <p class="font-semibold text-gray-900">Publication partagée</p>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">{{ $notification->donnees['message'] ?? 'Votre publication a été partagée' }}</p>
                                            
                                            @elseif($notification->type === 'groupe_nouvel_membre')
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-user-plus text-blue-500"></i>
                                                    <p class="font-semibold text-gray-900">Nouveau membre du groupe</p>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">{{ $notification->donnees['message'] ?? 'Un nouvel utilisateur a rejoint votre groupe' }}</p>
                                            
                                            @elseif($notification->type === 'groupe_membre_quitte')
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-user-times text-red-500"></i>
                                                    <p class="font-semibold text-gray-900">Membre a quitté</p>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">{{ $notification->donnees['message'] ?? 'Un utilisateur a quitté votre groupe' }}</p>
                                            
                                            @elseif($notification->type === 'nouveau_message')
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-envelope text-purple-500"></i>
                                                    <p class="font-semibold text-gray-900">Nouveau message</p>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">{{ $notification->donnees['message'] ?? 'Vous avez reçu un nouveau message' }}</p>
                                            
                                            @else
                                                <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</p>
                                                <p class="text-sm text-gray-600 mt-1">{{ $notification->donnees['message'] ?? 'Nouvelle notification' }}</p>
                                            @endif
                                        </div>
                                        @if(!$notification->read_at)
                                            <span class="inline-block w-3 h-3 bg-blue-500 rounded-full flex-shrink-0"></span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-3">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                                Marquer comme lu
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                                            <i class="fas fa-trash mr-1"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-bell text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-600 text-lg">Aucune notification pour le moment</p>
                    <p class="text-gray-500 text-sm mt-2">Vous serez notifié quand quelque chose se passe!</p>
                </div>
            @endif
        </div>
    </main>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
