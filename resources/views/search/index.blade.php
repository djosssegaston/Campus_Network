@extends('layouts.app')

@section('title', 'Recherche')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Recherche</h1>
        <p class="text-gray-600 mt-2">Trouvez des publications, utilisateurs et groupes</p>
    </div>

    <!-- Barre de recherche -->
    <form method="GET" action="{{ route('search.index') }}" class="mb-8">
        <div class="flex gap-4">
            <input 
                type="text" 
                name="q" 
                value="{{ $query }}" 
                placeholder="Rechercher..." 
                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
                minlength="2"
            >
            <select 
                name="type" 
                class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Tous</option>
                <option value="publication" {{ $type === 'publication' ? 'selected' : '' }}>Publications</option>
                <option value="utilisateur" {{ $type === 'utilisateur' ? 'selected' : '' }}>Utilisateurs</option>
                <option value="groupe" {{ $type === 'groupe' ? 'selected' : '' }}>Groupes</option>
            </select>
            <button 
                type="submit" 
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
                Rechercher
            </button>
        </div>
    </form>

    @if(empty($query))
        <!-- Message d'accueil -->
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-600 text-lg">Entrez un terme de recherche pour commencer</p>
        </div>
    @else
        <!-- Résultats -->
        @if($results['publications'] === null && $results['utilisateurs'] === null && $results['groupes'] === null)
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600 text-lg">Aucun résultat trouvé</p>
            </div>
        @else
            <!-- Publications -->
            @if($results['publications'] !== null && count($results['publications']))
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-newspaper mr-3 text-blue-600"></i>
                        Publications ({{ $results['publications']->total() }})
                    </h2>
                    <div class="space-y-4">
                        @foreach($results['publications'] as $publication)
                            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <a href="{{ route('publications.show', $publication->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            Auteur: {{ $publication->utilisateur->nom ?? 'Utilisateur supprimé' }}
                                        </a>
                                        <p class="text-gray-600 mt-2">{{ Str::limit($publication->contenu, 150) }}</p>
                                        @if($publication->groupe)
                                            <span class="inline-block mt-2 px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded">
                                                {{ $publication->groupe->nom }}
                                            </span>
                                        @endif
                                        <p class="text-gray-400 text-sm mt-3">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $publication->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($results['publications']->hasPages())
                        <div class="mt-6">
                            {{ $results['publications']->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Utilisateurs -->
            @if($results['utilisateurs'] !== null && count($results['utilisateurs']))
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-users mr-3 text-green-600"></i>
                        Utilisateurs ({{ $results['utilisateurs']->total() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($results['utilisateurs'] as $utilisateur)
                            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                                <div class="flex items-center gap-4 mb-4">
                                    @if($utilisateur->avatar_url)
                                        <img 
                                            src="{{ $utilisateur->avatar_url }}" 
                                            alt="{{ $utilisateur->nom }}"
                                            class="w-12 h-12 rounded-full object-cover"
                                        >
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('profile.show', $utilisateur->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            {{ $utilisateur->nom }}
                                        </a>
                                        @if($utilisateur->role)
                                            <p class="text-gray-500 text-sm">{{ $utilisateur->role->nom }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($utilisateur->filiere)
                                    <p class="text-gray-600 text-sm">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        {{ $utilisateur->filiere }}
                                    </p>
                                @endif
                                <p class="text-gray-400 text-xs mt-2">{{ $utilisateur->email }}</p>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($results['utilisateurs']->hasPages())
                        <div class="mt-6">
                            {{ $results['utilisateurs']->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Groupes -->
            @if($results['groupes'] !== null && count($results['groupes']))
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-layer-group mr-3 text-purple-600"></i>
                        Groupes ({{ $results['groupes']->total() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($results['groupes'] as $groupe)
                            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                                <a href="{{ route('groupes.show', $groupe->id) }}" class="text-blue-600 hover:text-blue-800 text-xl font-semibold">
                                    {{ $groupe->nom }}
                                </a>
                                <p class="text-gray-600 mt-2">{{ Str::limit($groupe->description, 100) }}</p>
                                <div class="flex gap-6 mt-4 text-gray-600 text-sm">
                                    <span>
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $groupe->utilisateurs_count }} membres
                                    </span>
                                    <span>
                                        <i class="fas fa-newspaper mr-1"></i>
                                        {{ $groupe->publications_count }} publications
                                    </span>
                                </div>
                                <a 
                                    href="{{ route('groupes.show', $groupe->id) }}" 
                                    class="inline-block mt-4 px-4 py-2 bg-purple-600 text-white text-sm rounded hover:bg-purple-700 transition"
                                >
                                    Voir le groupe
                                </a>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($results['groupes']->hasPages())
                        <div class="mt-6">
                            {{ $results['groupes']->links() }}
                        </div>
                    @endif
                </div>
            @endif
        @endif
    @endif
</div>
@endsection
