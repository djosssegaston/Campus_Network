<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// FormRequest : validation pour créer / mettre à jour une publication
class StorePublicationRequest extends FormRequest
{
    public function authorize()
    {
        return true; // l'autorisation sera gérée par les policies/controllers
    }

    public function rules()
    {
        return [
            'contenu' => 'nullable|string|max:5000|required_without:medias',
            'groupe_id' => 'nullable|exists:groupes,id',
            'visibilite' => 'required|in:publique,amis,groupe,prive',
            'medias.*' => 'sometimes|file|max:10240', // 10MB par fichier
        ];
    }

    public function messages()
    {
        return [
            'contenu.required_without' => 'Le contenu ou au moins un média est requis.',
            'visibilite.required' => 'La visibilité est requise.',
            'groupe_id.exists' => 'Le groupe sélectionné est invalide.',
            'medias.*.file' => 'Chaque média doit être un fichier valide.',
            'medias.*.max' => 'Chaque fichier ne doit pas dépasser 10MB.',
        ];
    }
}
