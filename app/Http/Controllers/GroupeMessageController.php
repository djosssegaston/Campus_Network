<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\GroupeMessage;
use App\Models\GroupeSetting;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupeMessageController extends Controller
{
    /**
     * Afficher les messages d'un groupe.
     */
    public function index(Groupe $groupe): View
    {
        $messages = $groupe->messages()
            ->with('utilisateur')
            ->with('medias')
            ->latest()
            ->paginate(20);

        return view('groupes.messages.index', [
            'groupe' => $groupe,
            'messages' => $messages,
        ]);
    }

    /**
     * Stocker un nouveau message dans le groupe.
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
        if (!$settings->autoriser_messages) {
            return back()->with('error', 'Les messages sont désactivés dans ce groupe.');
        }

        // Validation
        $validated = $request->validate([
            'contenu' => 'required_without:medias|string|max:5000',
            'medias' => 'nullable|array',
            'medias.*' => 'file|max:102400', // 100 MB par fichier
        ]);

        // Vérifier qu'il y a au moins du contenu ou un fichier
        if (empty($validated['contenu']) && (!$request->hasFile('medias') || count($request->file('medias')) === 0)) {
            return back()->withErrors(['contenu' => 'Veuillez entrer un message ou ajouter un fichier.']);
        }

        // Déterminer le type de message
        $type = 'text';
        if ($request->hasFile('medias') && count($request->file('medias')) > 0) {
            $firstFile = $request->file('medias')[0];
            $mimeType = $firstFile->getMimeType();

            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $type = 'video';
            } elseif (str_starts_with($mimeType, 'audio/')) {
                $type = 'audio';
            } else {
                $type = 'fichier';
            }
        }

        // Créer le message
        $message = GroupeMessage::create([
            'groupe_id' => $groupe->id,
            'utilisateur_id' => auth()->id(),
            'contenu' => $validated['contenu'] ?? null,
            'type' => $type,
        ]);

        // Traiter les médias si présents
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $file) {
                $this->storeMedia($message, $file);
            }
        }

        return back()->with('success', 'Message envoyé avec succès!');
    }

    /**
     * Supprimer un message.
     */
    public function destroy(Groupe $groupe, GroupeMessage $message): RedirectResponse
    {
        // Vérifier les permissions
        if ($message->utilisateur_id !== auth()->id() && $groupe->admin_id !== auth()->id()) {
            return back()->with('error', 'Vous n\'avez pas la permission de supprimer ce message.');
        }

        // Supprimer les médias associés
        foreach ($message->medias as $media) {
            $media->supprimerFichier();
        }

        $message->delete();

        return back()->with('success', 'Message supprimé avec succès!');
    }

    /**
     * Stocker un média associé au message.
     */
    private function storeMedia(GroupeMessage $message, $file): void
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('groupes/' . $message->groupe_id . '/messages', $fileName, 'public');

        $message->medias()->create([
            'nom_fichier' => $file->getClientOriginalName(),
            'chemin' => $path,
            'type_mime' => $file->getMimeType(),
            'taille' => $file->getSize(),
        ]);
    }
}
