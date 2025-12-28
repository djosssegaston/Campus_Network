@extends('layouts.app')

@section('title', $groupe->nom)

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <a href="{{ route('groupes.index') }}" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">
        <i class="fas fa-arrow-left mr-1"></i>Retour aux groupes
    </a>

    <!-- Header du groupe -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-48"></div>
        
        <div class="px-8 pb-6">
            <div class="flex justify-between items-start -mt-20 relative z-10">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $groupe->nom }}</h1>
                    
                    <div class="flex items-center gap-4 text-gray-600 mb-4">
                        <span>
                            <i class="fas fa-users mr-1"></i>
                            {{ $groupe->utilisateurs->count() }} membres
                        </span>
                        <span class="px-3 py-1 bg-{{ $groupe->visibilite === 'public' ? 'green' : 'gray' }}-100 text-{{ $groupe->visibilite === 'public' ? 'green' : 'gray' }}-700 rounded-full text-sm">
                            {{ $groupe->visibilite === 'public' ? 'Public' : 'Privé' }}
                        </span>
                        <span>
                            <i class="fas fa-calendar mr-1"></i>
                            Créé {{ $groupe->created_at->diffForHumans() }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-4 max-w-2xl">
                        {{ $groupe->description ?? 'Pas de description pour ce groupe' }}
                    </p>

                    @auth
                        @if($groupe->utilisateurs->contains(auth()->user()))
                            <button onclick="leaveGroup('{{ $groupe->id }}')" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>Quitter le groupe
                            </button>
                        @else
                            <button onclick="joinGroup('{{ $groupe->id }}')" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-sign-in-alt mr-2"></i>Rejoindre le groupe
                            </button>
                        @endif
                    @endauth
                </div>

                @if($groupe->admin_id === auth()->id())
                    <div class="text-right">
                        <p class="text-sm text-gray-500 mb-2">Vous êtes admin</p>
                        <a href="{{ route('groupe-settings.edit', $groupe->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                            <i class="fas fa-cog mr-1"></i>Paramètres
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Publications du groupe -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Publications</h2>

        @auth
            @if($groupe->utilisateurs->contains(auth()->user()))
                <!-- Formulaire de création de publication -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="font-semibold text-gray-900 mb-4">Créer une publication</h3>
                    
                    <form action="{{ route('groupe-publications.store', $groupe->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <textarea 
                                name="contenu" 
                                placeholder="Partagez quelque chose avec le groupe..." 
                                rows="4"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            ></textarea>
                            @error('contenu')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fichiers multimédias -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-images mr-2"></i>Ajouter des médias (image, vidéo, audio, fichier)
                            </label>
                            <input 
                                type="file" 
                                name="medias[]" 
                                multiple
                                accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.zip"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <p class="text-xs text-gray-500 mt-1">Max 100MB par fichier, formats: images, vidéos, audio, documents</p>
                            @error('medias.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>Publier
                        </button>
                    </form>
                </div>
            @endif
        @endauth

        @if($publications->count() > 0)
            <div class="space-y-6">
                @foreach($publications as $publication)
                    <div class="border-b border-gray-200 pb-6 last:border-b-0">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($publication->utilisateur->nom, 0, 2) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $publication->utilisateur->nom }}</p>
                                <p class="text-sm text-gray-500">{{ $publication->created_at->diffForHumans() }}</p>
                            </div>
                            @if($publication->utilisateur_id === auth()->id() || $groupe->admin_id === auth()->id())
                                <div class="flex gap-2">
                                    <button onclick="deletePublication('{{ $publication->id }}')" class="text-red-500 hover:text-red-700 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <p class="text-gray-700 mb-3">{{ $publication->contenu }}</p>

                        <!-- Affichage des médias -->
                        @if($publication->medias->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                @foreach($publication->medias as $media)
                                    <div class="rounded-lg overflow-hidden bg-gray-100">
                                        @php
                                            $mimeType = $media->type_mime;
                                            $isImage = str_starts_with($mimeType, 'image/');
                                            $isVideo = str_starts_with($mimeType, 'video/');
                                            $isAudio = str_starts_with($mimeType, 'audio/');
                                        @endphp

                                        @if($isImage)
                                            <img src="{{ media_url($media->chemin) }}" alt="{{ $media->nom_fichier }}" class="w-full h-64 object-cover">
                                        @elseif($isVideo)
                                            <video controls class="w-full h-64">
                                                <source src="{{ media_url($media->chemin) }}" type="{{ $mimeType }}">
                                                Votre navigateur ne supporte pas la vidéo.
                                            </video>
                                        @elseif($isAudio)
                                            <div class="w-full h-20 flex items-center justify-center bg-gradient-to-r from-blue-400 to-purple-600">
                                                <audio controls class="w-full">
                                                    <source src="{{ media_url($media->chemin) }}" type="{{ $mimeType }}">
                                                    Votre navigateur ne supporte pas l'audio.
                                                </audio>
                                            </div>
                                        @else
                                            <div class="w-full h-32 flex flex-col items-center justify-center">
                                                <i class="fas fa-file text-4xl text-gray-400 mb-2"></i>
                                                <a href="{{ media_url($media->chemin) }}" download class="text-blue-600 hover:text-blue-800 text-sm font-medium truncate px-2">
                                                    {{ $media->nom_fichier }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex gap-6 text-sm text-gray-600">
                            <button class="hover:text-blue-600 transition">
                                <i class="fas fa-heart mr-1"></i>{{ $publication->reactions->count() }}
                            </button>
                            <button class="hover:text-blue-600 transition">
                                <i class="fas fa-comment mr-1"></i>{{ $publication->commentaires->count() }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($publications->hasPages())
                <div class="mt-6">
                    {{ $publications->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">Aucune publication dans ce groupe pour le moment</p>
            </div>
        @endif
    </div>

    <!-- Messages du groupe -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Messagerie du Groupe</h2>

        @auth
            @if($groupe->utilisateurs->contains(auth()->user()))
                <!-- Formulaire de création de message -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h3 class="font-semibold text-gray-900 mb-4">Envoyer un message</h3>
                    
                    <form action="{{ route('groupe-messages.store', $groupe->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <textarea 
                                name="contenu" 
                                placeholder="Votre message..." 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                            ></textarea>
                            @error('contenu')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fichiers multimédias -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-paperclip mr-2"></i>Ajouter des fichiers (optionnel)
                            </label>
                            <input 
                                type="file" 
                                name="medias[]" 
                                multiple
                                accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.zip"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                            <p class="text-xs text-gray-500 mt-1">Max 100MB par fichier</p>
                            @error('medias.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium"
                        >
                            <i class="fas fa-send mr-2"></i>Envoyer
                        </button>
                    </form>
                </div>
            @endif
        @endauth

        @if($messages->count() > 0)
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach($messages as $message)
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                    {{ substr($message->utilisateur->nom, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $message->utilisateur->nom }}</p>
                                    <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($message->utilisateur_id === auth()->id() || $groupe->admin_id === auth()->id())
                                <button onclick="deleteMessage('{{ $groupe->id }}', '{{ $message->id }}')" class="text-red-600 hover:text-red-900 text-xs">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            @endif
                        </div>

                        @if($message->contenu)
                            <p class="text-gray-700 text-sm mb-3">{{ $message->contenu }}</p>
                        @endif

                        @if($message->medias->count() > 0)
                            <div class="grid grid-cols-2 gap-2 mt-3">
                                @foreach($message->medias as $media)
                                    @php
                                        $mimeType = $media->type_mime;
                                        $isImage = str_starts_with($mimeType, 'image/');
                                        $isVideo = str_starts_with($mimeType, 'video/');
                                        $isAudio = str_starts_with($mimeType, 'audio/');
                                    @endphp

                                    @if($isImage)
                                        <img src="{{ media_url($media->chemin) }}" alt="{{ $media->nom_fichier }}" class="w-full h-32 object-cover rounded">
                                    @elseif($isVideo)
                                        <video controls class="w-full h-32 rounded">
                                            <source src="{{ media_url($media->chemin) }}" type="{{ $mimeType }}">
                                            Votre navigateur ne supporte pas la vidéo.
                                        </video>
                                    @elseif($isAudio)
                                        <div class="w-full h-12 flex items-center justify-center bg-gradient-to-r from-green-400 to-blue-600 rounded">
                                            <audio controls class="w-full">
                                                <source src="{{ media_url($media->chemin) }}" type="{{ $mimeType }}">
                                                Votre navigateur ne supporte pas l'audio.
                                            </audio>
                                        </div>
                                    @else
                                        <div class="w-full h-12 flex items-center justify-center bg-gray-200 rounded">
                                            <a href="{{ media_url($media->chemin) }}" download class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                <i class="fas fa-download mr-1"></i>{{ $media->nom_fichier }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($messages->hasPages())
                <div class="mt-6">
                    {{ $messages->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-comment-slash text-4xl mb-3"></i>
                <p>Aucun message dans ce groupe pour le moment</p>
            </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
async function joinGroup(groupeId) {
    try {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('groupes.join', ':id') }}`.replace(':id', groupeId);
        form.innerHTML = `
            @csrf
        `;
        document.body.appendChild(form);
        form.submit();
        form.remove();
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la tentative de rejoindre le groupe');
    }
}

async function leaveGroup(groupeId) {
    if (!confirm('Êtes-vous sûr de vouloir quitter ce groupe?')) return;

    try {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('groupes.leave', ':id') }}`.replace(':id', groupeId);
        form.innerHTML = `
            @csrf
        `;
        document.body.appendChild(form);
        form.submit();
        form.remove();
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la tentative de quitter le groupe');
    }
}

function deletePublication(publicationId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette publication?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ route('groupe-publications.destroy', [$groupe->id, ':id']) }}`.replace(':id', publicationId);
    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;
    document.body.appendChild(form);
    form.submit();
    form.remove();
}

function deleteMessage(groupeId, messageId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce message?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ route('groupe-messages.destroy', [':groupe_id', ':message_id']) }}`.replace(':groupe_id', groupeId).replace(':message_id', messageId);
    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;
    document.body.appendChild(form);
    form.submit();
    form.remove();
}
</script>
@endsection
