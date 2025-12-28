<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicationRequest;
use App\Http\Resources\PublicationResource;
use App\Http\Resources\PublicationCollection;
use App\Models\Publication;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Contrôleur API pour les publications (feed, CRUD)
class PublicationController extends Controller
{
    // Liste paginée des publications (feed public)
    public function index(Request $request)
    {
        $query = Publication::with(['utilisateur', 'medias'])
            ->where('statut', 'actif')
            ->latest();

        $paginator = $query->paginate(20);

        return new PublicationCollection($paginator);
    }

    // Feed personnalisé (utilisateur + groupes)
    public function feed(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return $this->index($request);
        }

        // Récupère les IDs des groupes de l'utilisateur
        $groupIds = $user->groupes()->pluck('groupes.id')->toArray();

        $query = Publication::with(['utilisateur', 'medias'])
            ->where('statut', 'actif')
            ->where(function ($q) use ($user, $groupIds) {
                $q->where('utilisateur_id', $user->id)
                  ->orWhereIn('groupe_id', $groupIds)
                  ->orWhere('visibilite', 'publique');
            })
            ->latest();

        return new PublicationCollection($query->paginate(20));
    }

    // Création d'une publication
    public function store(StorePublicationRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();

        $publication = Publication::create([
            'utilisateur_id' => $user->id,
            'groupe_id' => $data['groupe_id'] ?? null,
            'contenu' => $data['contenu'] ?? null,
            'visibilite' => $data['visibilite'] ?? 'publique',
            'statut' => 'actif',
        ]);

        // Gestion des fichiers médias (si fourni)
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $file) {
                $path = $file->store('public/uploads');
                $media = Media::create([
                    'model_id' => $publication->id,
                    'model_type' => Publication::class,
                    'nom_fichier' => $file->getClientOriginalName(),
                    'chemin' => $path,
                    'type_mime' => $file->getClientMimeType(),
                    'taille' => $file->getSize(),
                ]);
            }
        }

        $publication->load('utilisateur', 'medias');

        return new PublicationResource($publication);
    }

    // Affiche une publication
    public function show($id)
    {
        $publication = Publication::with(['utilisateur', 'medias', 'commentaires'])->findOrFail($id);
        return new PublicationResource($publication);
    }

    // Mise à jour d'une publication
    public function update(StorePublicationRequest $request, $id)
    {
        $publication = Publication::findOrFail($id);
        $user = $request->user();

        // Vérification basique de permission: auteur ou administrateur
        if (!$publication->peutModifier($user)) {
            return response()->json(['message' => 'Non autorisé à modifier cette publication.'], 403);
        }

        $data = $request->validated();

        $publication->update([
            'contenu' => $data['contenu'] ?? $publication->contenu,
            'visibilite' => $data['visibilite'] ?? $publication->visibilite,
        ]);

        $publication->load('utilisateur', 'medias');

        return new PublicationResource($publication);
    }

    // Suppression logique / marquage supprimé
    public function destroy(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);
        $user = $request->user();

        if ($publication->utilisateur_id !== $user->id && !$user->estAdmin()) {
            return response()->json(['message' => 'Non autorisé à supprimer cette publication.'], 403);
        }

        $publication->statut = 'supprime';
        $publication->save();

        return response()->json(['message' => 'Publication supprimée.']);
    }
}
