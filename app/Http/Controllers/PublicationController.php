<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Media;
use App\Http\Requests\StorePublicationRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

/**
 * PublicationController - Gère les publications (Web)
 */
class PublicationController extends Controller
{
    /**
     * Affiche le formulaire de création de publication
     */
    public function create(): View
    {
        return view('publications.create');
    }

    /**
     * Stocke une nouvelle publication (via formulaire Web)
     * 
     * @param StorePublicationRequest $request
     * @return RedirectResponse
     */
    public function store(StorePublicationRequest $request): RedirectResponse
    {
        // Récupère les données validées
        $validated = $request->validated();
        
        // Ajoute l'ID de l'utilisateur connecté
        $validated['utilisateur_id'] = auth()->id();
        
        // Crée la publication
        $publication = Publication::create($validated);

        // Traite les fichiers uploadés (images, vidéos, sons)
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $file) {
                // Valide le type de fichier
                $mime = $file->getMimeType();
                $extension = strtolower($file->getClientOriginalExtension());
                
                // Détermine le type
                if (str_starts_with($mime, 'image/')) {
                    $type = 'image';
                } elseif (str_starts_with($mime, 'video/')) {
                    $type = 'video';
                } elseif (str_starts_with($mime, 'audio/')) {
                    $type = 'audio';
                } else {
                    continue; // Ignore les types non supportés
                }
                
                // Génère un nom unique
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                // Stocke le fichier
                $path = $file->storeAs('medias', $filename, 'public');
                
                // Crée l'enregistrement Media via la relation polymorphique
                $publication->medias()->create([
                    'nom_fichier' => $file->getClientOriginalName(),
                    'chemin' => 'medias/' . $filename,
                    'type_mime' => $mime,
                    'taille' => $file->getSize(),
                ]);
            }
        }

        // Retour avec message de succès
        return redirect()
            ->route('feed.index')
            ->with('success', 'Publication créée avec succès! ✨');
    }

    /**
     * Affiche le détail d'une publication
     */
    public function show(Publication $publication): View
    {
        $publication->load(['utilisateur', 'commentaires.utilisateur', 'reactions.utilisateur']);
        
        return view('publications.show', compact('publication'));
    }

    /**
     * Supprime une publication (soft delete)
     */
    public function destroy(Publication $publication): RedirectResponse
    {
        // Vérifie que l'utilisateur est propriétaire
        if ($publication->utilisateur_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }

        $publication->delete();

        return redirect()
            ->route('feed.index')
            ->with('success', 'Publication supprimée.');
    }
}
