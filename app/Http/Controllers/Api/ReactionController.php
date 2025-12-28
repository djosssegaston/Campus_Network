<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Models\Publication;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReactionController extends Controller
{
    use AuthenticatedUser;
    /**
     * Get reactions for a publication.
     */
    public function index($publicationId): JsonResponse
    {
        $publication = Publication::findOrFail($publicationId);
        
        $reactions = $publication->reactions()
            ->with('utilisateur')
            ->get()
            ->groupBy('type');

        return response()->json(['data' => $reactions]);
    }

    /**
     * Add a reaction to a publication.
     */
    public function store(Request $request, $publicationId): JsonResponse
    {
        $publication = Publication::findOrFail($publicationId);

        $validated = $request->validate([
            'type' => 'required|in:like,love,haha,sad,angry,wow',
        ]);

        // Check if user already reacted
        $existing = $publication->reactions()
            ->where('utilisateur_id', $this->userId())
            ->first();

        if ($existing) {
            $existing->update(['type' => $validated['type']]);
            return response()->json([
                'message' => 'Réaction mise à jour',
                'data' => $existing,
            ]);
        }

        $reaction = $publication->reactions()->create([
            'utilisateur_id' => $this->userId(),
            'type' => $validated['type'],
        ]);

        return response()->json([
            'message' => 'Réaction ajoutée',
            'data' => $reaction->load('utilisateur'),
        ], 201);
    }

    /**
     * Remove a reaction.
     */
    public function destroy($id): JsonResponse
    {
        $reaction = Reaction::findOrFail($id);

        // Check authorization
        $user = $this->user();
        if (!$user || ($reaction->utilisateur_id !== $user->id && !$user->estAdmin())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $reaction->delete();

        return response()->json(['message' => 'Réaction supprimée']);
    }
}
