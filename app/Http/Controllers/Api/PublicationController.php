<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Http\Requests\StorePublicationRequest;
use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublicationController extends Controller
{
    use AuthenticatedUser;
    /**
     * Display a paginated list of publications.
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $publications = Publication::with(['utilisateur', 'commentaires', 'reactions'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $publications->items(),
            'current_page' => $publications->currentPage(),
            'last_page' => $publications->lastPage(),
            'next_page_url' => $publications->nextPageUrl(),
            'prev_page_url' => $publications->previousPageUrl(),
            'total' => $publications->total(),
        ]);
    }

    /**
     * Store a newly created publication.
     */
    public function store(StorePublicationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Add current user ID
        $validated['utilisateur_id'] = $this->userId();
        $publication = Publication::create($validated);

        // Handle media uploads if present
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $media) {
                $path = $media->store('publications', 'public');
                $publication->medias()->create(['chemin' => $path]);
            }
        }

        return response()->json([
            'message' => 'Publication créée avec succès',
            'data' => $publication->load(['utilisateur', 'medias']),
        ], 201);
    }

    /**
     * Display the specified publication.
     */
    public function show($id): JsonResponse
    {
        $publication = Publication::with(['utilisateur', 'commentaires.utilisateur', 'reactions', 'medias'])
            ->findOrFail($id);

        return response()->json(['data' => $publication]);
    }

    /**
     * Update the specified publication.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $publication = Publication::findOrFail($id);

        // Check authorization
        if ($publication->utilisateur_id !== $this->userId()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'contenu' => 'required|string',
            'visibilite' => 'nullable|in:public,amis,groupe,prive',
        ]);

        $publication->update($validated);

        return response()->json([
            'message' => 'Publication mise à jour',
            'data' => $publication,
        ]);
    }

    /**
     * Delete the specified publication.
     */
    public function destroy($id): JsonResponse
    {
        $publication = Publication::findOrFail($id);

        // Check authorization - owner or admin
        $user = $this->user();
        
        if (!$user || ($publication->utilisateur_id !== $user->id && !$user->estAdmin())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $publication->delete();

        return response()->json(['message' => 'Publication supprimée']);
    }
}
