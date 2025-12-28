<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    // Ajoute ou met à jour une réaction sur une publication
    public function storePublication(Request $request, Publication $publication)
    {
        $data = $request->validate([
            'type' => 'required|string|max:20',
        ]);

        $user = $request->user();

        $existing = $publication->reactions()->where('utilisateur_id', $user->id)->first();

        if ($existing) {
            if ($existing->type === $data['type']) {
                $existing->delete();
                return response()->json(['message' => 'Réaction supprimée.']);
            }
            $existing->update(['type' => $data['type']]);
            return response()->json(['reaction' => $existing]);
        }

        $reaction = $publication->reactions()->create([
            'utilisateur_id' => $user->id,
            'type' => $data['type'],
        ]);

        return response()->json(['reaction' => $reaction], 201);
    }

    // Supprimer une réaction explicite sur une publication
    public function destroyPublication(Request $request, Publication $publication)
    {
        $user = $request->user();
        $existing = $publication->reactions()->where('utilisateur_id', $user->id)->first();
        if ($existing) {
            $existing->delete();
        }
        return response()->json(['message' => 'Réaction supprimée si existante.']);
    }

    // Ajoute ou met à jour une réaction sur un commentaire
    public function storeCommentaire(Request $request, Commentaire $commentaire)
    {
        $data = $request->validate([
            'type' => 'required|string|max:20',
        ]);

        $user = $request->user();

        $existing = $commentaire->reactions()->where('utilisateur_id', $user->id)->first();

        if ($existing) {
            if ($existing->type === $data['type']) {
                $existing->delete();
                return response()->json(['message' => 'Réaction supprimée.']);
            }
            $existing->update(['type' => $data['type']]);
            return response()->json(['reaction' => $existing]);
        }

        $reaction = $commentaire->reactions()->create([
            'utilisateur_id' => $user->id,
            'type' => $data['type'],
        ]);

        return response()->json(['reaction' => $reaction], 201);
    }

    // Supprimer une réaction explicite sur un commentaire
    public function destroyCommentaire(Request $request, Commentaire $commentaire)
    {
        $user = $request->user();
        $existing = $commentaire->reactions()->where('utilisateur_id', $user->id)->first();
        if ($existing) {
            $existing->delete();
        }
        return response()->json(['message' => 'Réaction supprimée si existante.']);
    }
}
