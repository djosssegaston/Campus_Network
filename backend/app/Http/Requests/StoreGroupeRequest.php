<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// FormRequest : validation pour création / mise à jour d'un groupe
class StoreGroupeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // les autorisations seront vérifiées dans le contrôleur
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'visibilite' => 'required|in:publique,fermee,privée',
            'categorie' => 'nullable|string|max:255',
            'regles' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom du groupe est requis.',
            'visibilite.required' => 'La visibilité du groupe est requise.',
            'visibilite.in' => 'La visibilité doit être publique, fermee ou privée.',
        ];
    }
}
