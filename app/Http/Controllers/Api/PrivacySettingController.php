<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Models\UserPrivacySetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PrivacySettingController extends Controller
{
    use AuthenticatedUser;

    /**
     * Récupère les paramètres de confidentialité de l'utilisateur actuel
     */
    public function show(): JsonResponse
    {
        $user = $this->user();
        $privacySettings = $user->privacySettings ?? $user->privacySettings()->create([]);

        return response()->json([
            'data' => $privacySettings,
        ]);
    }

    /**
     * Met à jour les paramètres de confidentialité
     */
    public function update(Request $request): JsonResponse
    {
        $user = $this->user();
        $privacySettings = $user->privacySettings ?? $user->privacySettings()->create([]);

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

        $privacySettings->update($validated);

        return response()->json([
            'message' => 'Paramètres de confidentialité mis à jour avec succès',
            'data' => $privacySettings,
        ]);
    }
}
