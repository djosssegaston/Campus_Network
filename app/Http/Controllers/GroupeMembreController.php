<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;

/**
 * Contrôleur pour gérer l'adhésion aux groupes
 */
class GroupeMembreController extends Controller
{
    /**
     * Rejoindre un groupe
     */
    public function join(Groupe $groupe): RedirectResponse
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur est déjà membre
        if ($groupe->utilisateurs()->where('utilisateur_id', $user->id)->exists()) {
            return redirect()->back()->with('info', 'Vous êtes déjà membre de ce groupe');
        }

        // Ajouter l'utilisateur au groupe
        $groupe->utilisateurs()->attach($user->id, ['role' => 'membre']);

        // Créer une notification à l'admin du groupe
        Notification::envoyer(
            $groupe->admin,
            'groupe_nouvel_membre',
            [
                'groupe_id' => $groupe->id,
                'groupe_nom' => $groupe->nom,
                'utilisateur_id' => $user->id,
                'utilisateur_nom' => $user->name,
                'message' => "{$user->name} a rejoint le groupe '{$groupe->nom}'"
            ]
        );

        return redirect()->back()->with('success', 'Vous avez rejoint le groupe avec succès!');
    }

    /**
     * Quitter un groupe
     */
    public function leave(Groupe $groupe): RedirectResponse
    {
        $user = auth()->user();

        // Empêcher l'admin de quitter
        if ($groupe->admin_id === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas quitter un groupe dont vous êtes administrateur');
        }

        // Retirer l'utilisateur du groupe
        $groupe->utilisateurs()->detach($user->id);

        // Créer une notification à l'admin
        Notification::envoyer(
            $groupe->admin,
            'groupe_membre_quitte',
            [
                'groupe_id' => $groupe->id,
                'groupe_nom' => $groupe->nom,
                'utilisateur_id' => $user->id,
                'utilisateur_nom' => $user->name,
                'message' => "{$user->name} a quitté le groupe '{$groupe->nom}'"
            ]
        );

        return redirect()->route('groupes.index')->with('success', 'Vous avez quitté le groupe');
    }

    /**
     * Vérifier si l'utilisateur est membre
     */
    public function estMembre(Groupe $groupe): bool
    {
        return $groupe->utilisateurs()->where('utilisateur_id', auth()->id())->exists();
    }
}
