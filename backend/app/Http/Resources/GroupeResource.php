<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// Ressource API pour représenter un groupe
class GroupeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
            'visibilite' => $this->visibilite,
            'categorie' => $this->categorie,
            'regles' => $this->regles ?? [],
            'membre_count' => $this->membres()->count(),
            'publication_count' => $this->publications()->count(),
            'admin' => $this->whenLoaded('admin', function () {
                return new UtilisateurResource($this->admin);
            }),
            'derniere_publication' => $this->whenLoaded('publications', function () {
                $p = $this->publications()->latest()->first();
                return $p ? [
                    'id' => $p->id,
                    'contenu' => substr($p->contenu, 0, 200),
                    'date' => $p->created_at ? $p->created_at->toDateTimeString() : null,
                ] : null;
            }),
        ];
    }
}
