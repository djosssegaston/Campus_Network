<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\GroupeSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GroupeSettingController extends Controller
{
    /**
     * Afficher le formulaire des paramètres du groupe.
     */
    public function edit(Groupe $groupe): View
    {
        // Vérifier que l'utilisateur est admin du groupe
        if ($groupe->admin_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas la permission d\'accéder à cette ressource.');
        }

        $settings = $groupe->getSettings();

        return view('groupes.settings', [
            'groupe' => $groupe,
            'settings' => $settings,
        ]);
    }

    /**
     * Mettre à jour les paramètres du groupe.
     */
    public function update(Request $request, Groupe $groupe): RedirectResponse
    {
        // Vérifier que l'utilisateur est admin du groupe
        if ($groupe->admin_id !== auth()->id()) {
            return back()->with('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }

        // Validation
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'visibilite' => 'required|in:public,prive,secret',
            'categorie' => 'nullable|string|max:255',
            'moderation_requise' => 'boolean',
            'autoriser_messages' => 'boolean',
            'autoriser_publications' => 'boolean',
            'autoriser_medias' => 'boolean',
            'permission_publication' => 'required|in:tous,moderateurs,admin',
            'permission_message' => 'required|in:tous,membres,admin',
            'mots_cles_interdits' => 'nullable|string',
        ]);

        // Mettre à jour le groupe
        $groupe->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'visibilite' => $validated['visibilite'],
            'categorie' => $validated['categorie'] ?? null,
        ]);

        // Traiter les mots-clés interdits
        $motsClesInterdits = null;
        if ($validated['mots_cles_interdits']) {
            $motsClesInterdits = array_map('trim', explode(',', $validated['mots_cles_interdits']));
        }

        // Mettre à jour ou créer les paramètres
        $settings = $groupe->getSettings();
        $settings->update([
            'moderation_requise' => $validated['moderation_requise'] ?? false,
            'autoriser_messages' => $validated['autoriser_messages'] ?? false,
            'autoriser_publications' => $validated['autoriser_publications'] ?? false,
            'autoriser_medias' => $validated['autoriser_medias'] ?? false,
            'permission_publication' => $validated['permission_publication'],
            'permission_message' => $validated['permission_message'],
            'mots_cles_interdits' => $motsClesInterdits,
        ]);

        return redirect()->route('groupes.show', $groupe->id)
            ->with('success', 'Paramètres du groupe mis à jour avec succès!');
    }

    /**
     * Supprimer le groupe.
     */
    public function destroy(Groupe $groupe): RedirectResponse
    {
        // Vérifier que l'utilisateur est admin du groupe
        if ($groupe->admin_id !== auth()->id()) {
            return back()->with('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }

        $groupe->delete();

        return redirect()->route('groupes.index')
            ->with('success', 'Le groupe a été supprimé avec succès!');
    }
}
