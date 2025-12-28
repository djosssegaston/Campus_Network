<?php

namespace App\Http\Controllers;

use App\Models\UserPrivacySetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrivacySettingController extends Controller
{
    /**
     * Affiche la page des paramètres de confidentialité
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $privacySettings = $user->getOrCreatePrivacySettings();

        return view('profile.privacy-settings', [
            'privacySettings' => $privacySettings,
        ]);
    }

    /**
     * Met à jour les paramètres de confidentialité
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $privacySettings = $user->getOrCreatePrivacySettings();

        $validated = $request->validate([
            'profil_visibilite' => ['required', 'in:public,prive,amis'],
            'messages_acceptes' => ['required', 'in:tous,amis,personne'],
            'publications_lisibles' => ['required', 'in:public,amis,prive'],
            'commentaires_acceptes' => ['required', 'in:tous,amis,personne'],
            'groupes_visibles' => ['required', 'in:public,prive'],
            'afficher_contacts' => ['nullable', 'boolean'],
            'afficher_groupes' => ['nullable', 'boolean'],
            'afficher_activite' => ['nullable', 'boolean'],
            'mentions_autorisees' => ['nullable', 'boolean'],
            'notifier_requetes_contact' => ['nullable', 'boolean'],
            'notifier_commentaires' => ['nullable', 'boolean'],
            'notifier_reactions' => ['nullable', 'boolean'],
        ]);

        // Convertir les checkboxes
        $validated['afficher_contacts'] = $request->has('afficher_contacts');
        $validated['afficher_groupes'] = $request->has('afficher_groupes');
        $validated['afficher_activite'] = $request->has('afficher_activite');
        $validated['mentions_autorisees'] = $request->has('mentions_autorisees');
        $validated['notifier_requetes_contact'] = $request->has('notifier_requetes_contact');
        $validated['notifier_commentaires'] = $request->has('notifier_commentaires');
        $validated['notifier_reactions'] = $request->has('notifier_reactions');

        $privacySettings->update($validated);

        return redirect()->route('privacy-settings.index')
            ->with('success', 'Paramètres de confidentialité mis à jour avec succès.');
    }
}
