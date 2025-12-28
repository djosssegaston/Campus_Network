@extends('layouts.app')

@section('title', 'Feed')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    @php
        $user = auth()->user();
        $roleSlug = optional($user->role)->slug ?? 'guest';
    @endphp

    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Feed</h1>
        <p class="text-gray-600 mt-2">Découvrez les dernières publications de votre réseau</p>
    </div>

    <!-- Actions par rôle -->
    <div class="mb-8">
        @if($roleSlug === 'etudiant')
            <a href="{{ route('publications.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                <i class="fas fa-plus mr-2"></i>
                <span>Créer une Publication</span>
            </a>

        @elseif($roleSlug === 'moderateur_groupe')
            <div class="flex gap-4">
                <button class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition shadow-md">
                    <i class="fas fa-eye mr-2"></i>
                    <span>Modérer</span>
                </button>
                <a href="#" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-md">
                    <i class="fas fa-chart-bar mr-2"></i>
                    <span>Statistiques</span>
                </a>
            </div>

        @elseif($roleSlug === 'admin_groupe')
            <div class="flex gap-4">
                <a href="#" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-md">
                    <i class="fas fa-cog mr-2"></i>
                    <span>Gérer le Groupe</span>
                </a>
                <a href="#" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-md">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span>Voir Analytics</span>
                </a>
            </div>

        @elseif($roleSlug === 'moderateur_global')
            <div class="flex gap-4">
                <button class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md">
                    <i class="fas fa-eye mr-2"></i>
                    <span>Modération Globale</span>
                </button>
                <button class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-md">
                    <i class="fas fa-chart-bar mr-2"></i>
                    <span>Rapports</span>
                </button>
            </div>

        @elseif($roleSlug === 'administrateur')
            <div class="flex gap-4">
                <a href="#" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md">
                    <i class="fas fa-users-cog mr-2"></i>
                    <span>Admin Utilisateurs</span>
                </a>
                <a href="#" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-md">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span>Analytics</span>
                </a>
            </div>

        @else
            <div class="flex gap-4">
                <a href="#" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition shadow-md">
                    <i class="fas fa-database mr-2"></i>
                    <span>Contrôle Total</span>
                </a>
                <a href="#" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-md">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span>Analytics</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Contenu du Feed -->
    <div class="max-w-3xl">
        @if($publications->count() > 0)
            @foreach($publications as $publication)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($publication->utilisateur->nom, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $publication->utilisateur->nom }}</p>
                                <p class="text-sm text-gray-500">{{ $publication->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if(in_array($roleSlug, ['moderateur_groupe', 'moderateur_global', 'administrateur', 'super_admin']))
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        @endif
                    </div>
                    <p class="text-gray-700 mb-4">{{ $publication->contenu }}</p>
                    
                    <!-- 📸 Affichage des médias -->
                    @if($publication->medias && $publication->medias->count() > 0)
                        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($publication->medias as $media)
                                @php
                                    $extension = strtolower(pathinfo($media->chemin, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isVideo = in_array($extension, ['mp4', 'avi', 'mov', 'mkv', 'webm']);
                                    $isAudio = in_array($extension, ['mp3', 'wav', 'ogg', 'm4a', 'flac']);
                                @endphp
                                
                                @if($isImage)
                                    <img src="{{ media_url($media->chemin) }}" alt="{{ $media->nom_fichier }}" class="rounded-lg max-h-96 object-cover w-full">
                                @elseif($isVideo)
                                    <video controls class="rounded-lg max-h-96 w-full">
                                        <source src="{{ media_url($media->chemin) }}" type="{{ $media->type_mime }}">
                                        Votre navigateur ne supporte pas la vidéo
                                    </video>
                                @elseif($isAudio)
                                    <div class="bg-gray-100 p-4 rounded-lg flex items-center gap-3">
                                        <i class="fas fa-music text-2xl text-blue-600"></i>
                                        <audio controls class="flex-1">
                                            <source src="{{ media_url($media->chemin) }}" type="{{ $media->type_mime }}">
                                            Votre navigateur ne supporte pas l'audio
                                        </audio>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="flex items-center gap-6 pt-4 border-t border-gray-200 text-gray-600">
                        <button onclick="toggleLike('{{ $publication->id }}')" class="flex items-center gap-2 hover:text-red-600 transition like-btn-{{ $publication->id }}" id="like-btn-{{ $publication->id }}">
                            <i class="fas fa-heart"></i>
                            <span class="text-sm like-count-{{ $publication->id }}">{{ $publication->reactions->count() }}</span>
                        </button>
                        <button class="flex items-center gap-2 hover:text-blue-600 transition" onclick="document.getElementById('comment-form-{{ $publication->id }}').scrollIntoView({behavior: 'smooth'})">
                            <i class="fas fa-comment"></i>
                            <span class="text-sm comment-count-{{ $publication->id }}">{{ $publication->commentaires->count() }}</span>
                        </button>
                        <button onclick="togglePartage('{{ $publication->id }}')" class="flex items-center gap-2 hover:text-green-600 transition partage-btn-{{ $publication->id }}" id="partage-btn-{{ $publication->id }}">
                            <i class="fas fa-share"></i>
                            <span class="text-sm partage-count-{{ $publication->id }}">{{ $publication->partages->count() }}</span>
                        </button>
                    </div>

                    <!-- Commentaires Section -->
                    <div class="mt-6 border-t border-gray-200 pt-4" id="comment-form-{{ $publication->id }}">
                        @include('partials.commentaires', ['publication' => $publication])
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($publications->hasPages())
                <div class="mt-6">
                    {{ $publications->links() }}
                </div>
            @endif
        @else
            <!-- Message vide si pas de publications -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">Plus de publications à afficher</p>
                @if($roleSlug === 'etudiant')
                    <p class="text-gray-500 text-sm mt-2">Soyez le premier à partager quelque chose!</p>
                @endif
            </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
async function toggleLike(publicationId) {
    try {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('reactions.store', ':id') }}`.replace(':id', publicationId);
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
        form.remove();
    } catch (error) {
        console.error('Erreur AJAX:', error);
        alert('Erreur lors du like');
    }
}

async function togglePartage(publicationId) {
    try {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('partages.store', ':id') }}`.replace(':id', publicationId);
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
        form.remove();
    } catch (error) {
        console.error('Erreur lors du partage:', error);
        alert('Erreur lors du partage');
    }
}
</script>
@endsection
