<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Http\Requests\StoreGroupeRequest;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GroupeController extends Controller
{
    use AuthenticatedUser;
    /**
     * Display a list of groups.
     */
    public function index(): JsonResponse
    {
        $groupes = Groupe::with('utilisateurs')
            ->withCount('utilisateurs', 'publications')
            ->get();

        return response()->json([
            'data' => $groupes,
        ]);
    }

    /**
     * Store a newly created group.
     */
    public function store(StoreGroupeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $groupe = Groupe::create([
            ...$validated,
            'admin_id' => $this->userId(),
        ]);

        // Ajouter le créateur au groupe
        $groupe->utilisateurs()->attach($this->userId(), ['role' => 'admin']);

        return response()->json([
            'message' => 'Groupe créé avec succès',
            'data' => $groupe,
        ], 201);
    }

    /**
     * Display the specified group.
     */
    public function show($id): JsonResponse
    {
        $groupe = Groupe::with(['utilisateurs', 'publications.utilisateur'])
            ->withCount('utilisateurs', 'publications')
            ->findOrFail($id);

        return response()->json([
            'data' => $groupe,
        ]);
    }

    /**
     * Get group publications.
     */
    public function publications($id): JsonResponse
    {
        $groupe = Groupe::findOrFail($id);
        
        $publications = $groupe->publications()
            ->with(['utilisateur', 'reactions', 'commentaires'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $publications->items(),
            'next_page_url' => $publications->nextPageUrl(),
        ]);
    }

    /**
     * Update the specified group.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $groupe = Groupe::findOrFail($id);

        // Check authorization - Only group admin can update
        if ($groupe->admin_id !== $this->userId()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibilite' => 'required|in:public,prive,secret',
            'categorie' => 'nullable|string|max:255',
        ]);

        $groupe->update($validated);

        return response()->json([
            'message' => 'Groupe mis à jour',
            'data' => $groupe,
        ]);
    }

    /**
     * Delete the specified group.
     */
    public function destroy($id): JsonResponse
    {
        $groupe = Groupe::findOrFail($id);

        // Check authorization - only group admin can delete
        $user = $this->user();
        if (!$user || ($groupe->admin_id !== $user->id && !$user->estAdmin())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $groupe->delete();

        return response()->json(['message' => 'Groupe supprimé']);
    }

    /**
     * Join a group.
     */
    public function join($id): JsonResponse
    {
        $groupe = Groupe::findOrFail($id);

        // Check if user is already a member
        if ($groupe->utilisateurs()->where('utilisateur_id', $this->userId())->exists()) {
            return response()->json(['message' => 'Déjà membre du groupe'], 400);
        }

        $groupe->utilisateurs()->attach($this->userId(), ['role' => 'membre']);

        return response()->json(['message' => 'Vous avez rejoint le groupe']);
    }

    /**
     * Leave a group.
     */
    public function leave($id): JsonResponse
    {
        $groupe = Groupe::findOrFail($id);

        // Check if user is a member
        if (!$groupe->utilisateurs()->where('utilisateur_id', $this->userId())->exists()) {
            return response()->json(['message' => 'Vous n\'êtes pas membre du groupe'], 400);
        }

        // Prevent admin from leaving if they are the only admin
        $isAdmin = $groupe->utilisateurs()->wherePivot('utilisateur_id', $this->userId())->wherePivot('role', 'admin')->exists();
        if ($isAdmin && $groupe->utilisateurs()->wherePivot('role', 'admin')->count() === 1) {
            return response()->json(['message' => 'Un admin doit rester dans le groupe'], 400);
        }

        $groupe->utilisateurs()->detach($this->userId());

        return response()->json(['message' => 'Vous avez quitté le groupe']);
    }
}
