<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentaireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'contenu' => 'required|string|min:2|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'contenu.required' => 'Le contenu du commentaire est obligatoire',
            'contenu.min' => 'Le commentaire doit contenir au moins 2 caractères',
            'contenu.max' => 'Le commentaire ne peut pas dépasser 1000 caractères',
        ];
    }
}
