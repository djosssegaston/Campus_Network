<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class GroupePublicationController extends Controller
{
    /**
     * Stocker une nouvelle publication dans le groupe.
     */
    public function store(Request $request, Groupe $groupe): RedirectResponse
    {
        // Vérifier que l'utilisateur est membre du groupe
        if (!$groupe->hasMember(auth()->user())) {
            return back()->with('error', 'Vous n\'êtes pas membre de ce groupe.');
        }

        // Récupérer les paramètres du groupe
        $settings = $groupe->getSettings();

        // Vérifier les permissions
        if (!$settings->autoriser_publications) {
            return back()->with('error', 'Les publications sont désactivées dans ce groupe.');
        }

        // Vérifier la permission de publier
        $user = auth()->user();
        $userRole = $groupe->utilisateurs()->find($user->id)?->pivot->role;

        if ($settings->permission_publication === 'moderateurs' && !in_array($userRole, ['admin', 'moderateur'])) {
            return back()->with('error', 'Seuls les modérateurs peuvent publier.');
        }

        if ($settings->permission_publication === 'admin' && $groupe->admin_id !== $user->id) {
            return back()->with('error', 'Seul l\'administrateur peut publier.');
        }

        // Validation
        $validated = $request->validate([
            'contenu' => 'required|string|max:5000',
            'medias' => 'nullable|array',
            'medias.*' => 'file|max:102400', // 100 MB par fichier
        ]);

        // Créer la publication
        // Note: statut accepte seulement 'actif', 'supprime', 'signale'
        // La modération se fera via révision manuelle
        $publication = Publication::create([
            'utilisateur_id' => auth()->id(),
            'groupe_id' => $groupe->id,
            'contenu' => $validated['contenu'],
            'visibilite' => $groupe->visibilite,
            'statut' => 'actif',  // Publication créée directement active
        ]);

        // Traiter les médias si présents
        if ($request->hasFile('medias') && $settings->autoriser_medias) {
            foreach ($request->file('medias') as $file) {
                $this->storeMedia($publication, $file);
            }
        }

        $message = 'Publication créée avec succès!';

        return back()->with('success', $message);
    }

    /**
     * Mettre à jour une publication.
     */
    public function update(Request $request, Groupe $groupe, Publication $publication): RedirectResponse
    {
        // Vérifier les permissions
        if ($publication->utilisateur_id !== auth()->id() && $groupe->admin_id !== auth()->id()) {
            return back()->with('error', 'Vous n\'avez pas la permission de modifier cette publication.');
        }

        // Validation
        $validated = $request->validate([
            'contenu' => 'required|string|max:5000',
        ]);

        $publication->update([
            'contenu' => $validated['contenu'],
        ]);

        return back()->with('success', 'Publication mise à jour avec succès!');
    }

    /**
     * Supprimer une publication.
     */
    public function destroy(Groupe $groupe, Publication $publication): RedirectResponse
    {
        // Vérifier les permissions
        if ($publication->utilisateur_id !== auth()->id() && $groupe->admin_id !== auth()->id()) {
            return back()->with('error', 'Vous n\'avez pas la permission de supprimer cette publication.');
        }

        // Supprimer les médias associés
        foreach ($publication->medias as $media) {
            $media->supprimerFichier();
        }

        $publication->delete();

        return back()->with('success', 'Publication supprimée avec succès!');
    }

    /**
     * Stocker un média associé à la publication.
     */
    private function storeMedia(Publication $publication, $file): void
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('groupes/' . $publication->groupe_id . '/publications', $fileName, 'public');

        $publication->medias()->create([
            'nom_fichier' => $file->getClientOriginalName(),
            'chemin' => $path,
            'type_mime' => $file->getMimeType(),
            'taille' => $file->getSize(),
        ]);
    }
}
