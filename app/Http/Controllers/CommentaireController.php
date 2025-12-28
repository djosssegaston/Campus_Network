<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Publication;
use App\Http\Requests\StoreCommentaireRequest;
use Illuminate\Http\RedirectResponse;

class CommentaireController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(StoreCommentaireRequest $request, Publication $publication): RedirectResponse
    {
        $validated = $request->validated();

        $publication->commentaires()->create([
            'utilisateur_id' => auth()->id(),
            'contenu' => $validated['contenu'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès!');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Commentaire $commentaire): RedirectResponse
    {
        // Authorization check
        if ($commentaire->utilisateur_id !== auth()->id() && !auth()->user()->estAdmin()) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        $publication = $commentaire->publication;
        $commentaire->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé!');
    }
}
