<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// FormRequest pour enregistrement et validation des données d'authentification
class StoreAuthRequest extends FormRequest
{
    // Autorisation
    public function authorize()
    {
        return true;
    }

    // Règles de validation
    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:8|confirmed',
            'filiere' => 'nullable|string|max:255',
            'annee_etude' => 'nullable|string|max:50',
        ];
    }

    // Messages d'erreur en français
    public function messages()
    {
        return [
            'nom.required' => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet e-mail est déjà utilisé.',
            'mot_de_passe.required' => 'Le mot de passe est requis.',
            'mot_de_passe.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'mot_de_passe.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
