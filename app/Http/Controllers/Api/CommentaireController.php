<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Http\Requests\StoreCommentaireRequest;
use App\Models\Publication;
use App\Models\Commentaire;
use Illuminate\Http\JsonResponse;

class CommentaireController extends Controller
{
    use AuthenticatedUser;
    /**
     * Get comments for a publication.
     */
    public function index($publicationId): JsonResponse
    {
        $publication = Publication::findOrFail($publicationId);
        
        $commentaires = $publication->commentaires()
            ->with('utilisateur')
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $commentaires->items(),
            'next_page_url' => $commentaires->nextPageUrl(),
        ]);
    }

    /**
     * Store a new comment.
     */
    public function store(StoreCommentaireRequest $request, $publicationId): JsonResponse
    {
        $publication = Publication::findOrFail($publicationId);
        $validated = $request->validated();

        $commentaire = $publication->commentaires()->create([
            'utilisateur_id' => $this->userId(),
            'contenu' => $validated['contenu'],
        ]);

        return response()->json([
            'message' => 'Commentaire ajouté',
            'data' => $commentaire->load('utilisateur'),
        ], 201);
    }

    /**
     * Delete a comment.
     */
    public function destroy($id): JsonResponse
    {
        $commentaire = Commentaire::findOrFail($id);

        // Check authorization - owner or admin
        $user = $this->user();
        
        if (!$user || ($commentaire->utilisateur_id !== $user->id && !$user->estAdmin())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $commentaire->delete();

        return response()->json(['message' => 'Commentaire supprimé']);
    }
}
