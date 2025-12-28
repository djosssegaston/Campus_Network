<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// Ressource API pour une publication
class PublicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'contenu' => $this->contenu,
            'visibilite' => $this->visibilite,
            'statut' => $this->statut,
            'date_creation' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'reactions_count' => $this->reactions()->count(),
            'commentaires_count' => $this->commentaires()->count(),
            'utilisateur' => new UtilisateurResource($this->whenLoaded('utilisateur')),
            'medias' => $this->whenLoaded('medias', function () {
                return $this->medias->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'nom_fichier' => $m->nom_fichier,
                        'url' => $m->url(),
                        'type_mime' => $m->type_mime,
                    ];
                });
            }),
        ];
    }
}
