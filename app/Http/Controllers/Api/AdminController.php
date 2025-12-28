<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Models\Utilisateur;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    use AuthenticatedUser;
    /**
     * Get site statistics.
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_users' => Utilisateur::count(),
                'total_publications' => Publication::count(),
                'active_users' => Utilisateur::where('updated_at', '>=', now()->subDays(30))->count(),
                'publications_this_month' => Publication::where('created_at', '>=', now()->startOfMonth())->count(),
            ],
        ]);
    }

    /**
     * Get all users with pagination.
     */
    public function users(Request $request): JsonResponse
    {
        $users = Utilisateur::with('role')
            ->latest()
            ->paginate($request->query('per_page', 15));

        return response()->json([
            'data' => $users->items(),
            'next_page_url' => $users->nextPageUrl(),
        ]);
    }

    /**
     * Get a specific user's details.
     */
    public function userDetail($id): JsonResponse
    {
        $user = Utilisateur::with('role', 'publications')->findOrFail($id);

        return response()->json([
            'data' => $user,
        ]);
    }

    /**
     * Update user information.
     */
    public function updateUser(Request $request, $id): JsonResponse
    {
        $user = Utilisateur::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'string|max:255',
            'prenom' => 'string|max:255',
            'email' => 'email|unique:utilisateurs,email,' . $id,
            'bio' => 'string|nullable|max:500',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Utilisateur mis à jour',
            'data' => $user,
        ]);
    }

    /**
     * Delete a user.
     */
    public function deleteUser($id): JsonResponse
    {
        $user = Utilisateur::findOrFail($id);

        // Prevent deleting current admin
        if ($user->id === $this->userId()) {
            return response()->json(['message' => 'Impossible de supprimer votre propre compte'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    /**
     * Get all publications (for moderation).
     */
    public function publications(Request $request): JsonResponse
    {
        $publications = Publication::with('utilisateur', 'commentaires')
            ->latest()
            ->paginate($request->query('per_page', 15));

        return response()->json([
            'data' => $publications->items(),
            'next_page_url' => $publications->nextPageUrl(),
        ]);
    }

    /**
     * Get flagged/reported content.
     */
    public function signalements(Request $request): JsonResponse
    {
        // This assumes a signalements table exists
        // For now, return empty if not implemented
        return response()->json([
            'data' => [],
            'message' => 'Signalements non encore implémentés',
        ]);
    }
}
