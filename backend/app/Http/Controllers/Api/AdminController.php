<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Publication;
use App\Models\Commentaire;
use App\Models\Groupe;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Get dashboard statistics
    public function stats()
    {
        return response()->json([
            'data' => [
                'total_users' => Utilisateur::count(),
                'total_publications' => Publication::count(),
                'total_comments' => Commentaire::count(),
                'total_groups' => Groupe::count(),
                'active_users_today' => Utilisateur::whereDate('updated_at', today())->count(),
                'new_publications_today' => Publication::whereDate('created_at', today())->count(),
            ]
        ]);
    }

    // Get list of users for admin
    public function users()
    {
        $users = Utilisateur::select('id', 'nom', 'email', 'filiere', 'annee_etude', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'data' => $users->items(),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
        ]);
    }

    // Get publications pending moderation
    public function publicationsPending()
    {
        $publications = Publication::with('utilisateur')
            ->where('statut', 'signale')
            ->orWhere('statut', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'data' => $publications->items(),
            'total' => $publications->total(),
        ]);
    }

    // Approve a publication
    public function approvePublication($id)
    {
        $publication = Publication::findOrFail($id);
        $publication->update(['statut' => 'actif']);

        return response()->json(['message' => 'Publication approuvée', 'data' => $publication]);
    }

    // Reject a publication
    public function rejectPublication($id)
    {
        $publication = Publication::findOrFail($id);
        $publication->update(['statut' => 'supprime']);

        return response()->json(['message' => 'Publication rejetée', 'data' => $publication]);
    }

    // Ban a user
    public function banUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->update(['statut' => 'banni']);

        return response()->json(['message' => 'Utilisateur banni', 'data' => $user]);
    }

    // Unban a user
    public function unbanUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->update(['statut' => 'actif']);

        return response()->json(['message' => 'Utilisateur débanui', 'data' => $user]);
    }
}
