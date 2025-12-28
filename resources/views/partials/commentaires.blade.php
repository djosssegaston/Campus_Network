<!-- Formulaire Commentaire -->
<form action="{{ route('commentaires.store', $publication) }}" method="POST" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
    @csrf
    <textarea name="contenu" placeholder="Écrivez un commentaire..." rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
    <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
        <i class="fas fa-comment mr-1"></i>Commenter
    </button>
</form>

<!-- Liste des Commentaires -->
@if($publication->commentaires && $publication->commentaires->count() > 0)
    <div class="mt-4 space-y-3">
        @foreach($publication->commentaires as $comment)
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold text-sm text-gray-900">{{ $comment->utilisateur->nom }}</p>
                        <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                    @if($comment->utilisateur_id === auth()->id() || auth()->user()->estAdmin())
                        <form action="{{ route('commentaires.destroy', $comment) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
                <p class="text-sm text-gray-700 mt-2">{{ $comment->contenu }}</p>
            </div>
        @endforeach
    </div>
@endif
