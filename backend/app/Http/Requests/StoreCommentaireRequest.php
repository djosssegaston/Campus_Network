<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// FormRequest : validation pour création / mise à jour de commentaire
class StoreCommentaireRequest extends FormRequest
{
    public function authorize()
    {
        return true; // l'autorisation sera gérée par policies dans le contrôleur
    }

    public function rules()
    {
        return [
            'contenu' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:commentaires,id',
        ];
    }

    public function messages()
    {
        return [
            'contenu.required' => 'Le contenu du commentaire est requis.',
            'contenu.max' => 'Le commentaire ne peut pas dépasser 2000 caractères.',
            'parent_id.exists' => 'Le commentaire parent est introuvable.',
        ];
    }
}
