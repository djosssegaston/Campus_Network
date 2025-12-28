<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Partage;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;

/**
 * Contrôleur pour gérer les partages de publications
 */
class PublicationPartageController extends Controller
{
    /**
     * Partager une publication
     */
    public function store(Publication $publication): RedirectResponse
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur a déjà partagé
        $existing = Partage::where('utilisateur_id', $user->id)
            ->where('publication_id', $publication->id)
            ->first();

        if ($existing) {
            // Si déjà partagée, retirer le partage
            $existing->delete();
            return redirect()->back()->with('success', 'Partage annulé');
        }

        // Créer le partage
        Partage::create([
            'utilisateur_id' => $user->id,
            'publication_id' => $publication->id,
        ]);

        // Notifier l'auteur de la publication
        Notification::envoyer(
            $publication->utilisateur,
            'publication_partagee',
            [
                'publication_id' => $publication->id,
                'utilisateur_id' => $user->id,
                'utilisateur_nom' => $user->name,
                'message' => "{$user->name} a partagé votre publication"
            ]
        );

        return redirect()->back()->with('success', 'Publication partagée avec succès!');
    }

    /**
     * Retirer un partage
     */
    public function destroy(Partage $partage): RedirectResponse
    {
        // Vérification des droits
        if ($partage->utilisateur_id !== auth()->id() && !auth()->user()->estAdmin()) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        $partage->delete();

        return redirect()->back()->with('success', 'Partage supprimé');
    }
}
