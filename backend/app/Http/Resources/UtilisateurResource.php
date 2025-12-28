<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// Ressource API pour représenter un utilisateur
class UtilisateurResource extends JsonResource
{
    /**
     * Transforme le resource en tableau
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'filiere' => $this->filiere,
            'annee_etude' => $this->annee_etude,
            'avatar_url' => $this->avatar_url,
            'role' => $this->role ? [
                'id' => $this->role->id,
                'nom' => $this->role->nom,
                'slug' => $this->role->slug,
            ] : null,
            'date_inscription' => $this->created_at ? $this->created_at->toDateTimeString() : null,
        ];
    }
}
