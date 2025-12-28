<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// Ressource API pour un commentaire
class CommentaireResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'contenu' => $this->contenu,
            'parent_id' => $this->parent_id,
            'statut' => $this->statut,
            'date_creation' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'utilisateur' => new UtilisateurResource($this->whenLoaded('utilisateur')),
            'enfants' => $this->whenLoaded('enfants', function () {
                return $this->enfants->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'contenu' => $c->contenu,
                        'utilisateur_id' => $c->utilisateur_id,
                    ];
                });
            }),
        ];
    }
}
