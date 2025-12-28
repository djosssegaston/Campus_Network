<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentaireRequest;
use App\Http\Resources\CommentaireResource;
use App\Models\Commentaire;
use App\Models\Publication;
use Illuminate\Http\Request;

// Contrôleur pour gérer les commentaires (création, modification, suppression)
class CommentaireController extends Controller
{
    // Crée un commentaire rattaché à une publication
    public function store(StoreCommentaireRequest $request, $publicationId)
    {
        $user = $request->user();

        $publication = Publication::findOrFail($publicationId);

        $data = $request->validated();

        $commentaire = Commentaire::create([
            'publication_id' => $publication->id,
            'utilisateur_id' => $user->id,
            'contenu' => $data['contenu'],
            'parent_id' => $data['parent_id'] ?? null,
            'statut' => 'visible',
        ]);

        $commentaire->load('utilisateur');

        // Retourne la ressource créée
        return new CommentaireResource($commentaire);
    }

    // Mise à jour d'un commentaire
    public function update(StoreCommentaireRequest $request, $id)
    {
        $user = $request->user();
        $commentaire = Commentaire::findOrFail($id);

        if ($commentaire->utilisateur_id !== $user->id && !$user->estAdmin()) {
            return response()->json(['message' => 'Non autorisé à modifier ce commentaire.'], 403);
        }

        $data = $request->validated();

        $commentaire->update([
            'contenu' => $data['contenu'] ?? $commentaire->contenu,
        ]);

        $commentaire->load('utilisateur');

        return new CommentaireResource($commentaire);
    }

    // Suppression (marquage) d'un commentaire
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $commentaire = Commentaire::findOrFail($id);

        if ($commentaire->utilisateur_id !== $user->id && !$user->estAdmin()) {
            return response()->json(['message' => 'Non autorisé à supprimer ce commentaire.'], 403);
        }

        $commentaire->statut = 'supprime';
        $commentaire->save();

        return response()->json(['message' => 'Commentaire supprimé.']);
    }
}
