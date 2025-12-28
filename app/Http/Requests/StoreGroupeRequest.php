<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupeRequest extends FormRequest
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
            'nom' => 'required|string|max:255|unique:groupes,nom',
            'description' => 'nullable|string|max:1000',
            'visibilite' => 'required|in:public,private',
            'categorie' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du groupe est obligatoire',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'nom.unique' => 'Ce nom de groupe existe déjà',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères',
            'visibilite.required' => 'La visibilité est obligatoire',
            'visibilite.in' => 'La visibilité doit être: public ou private',
        ];
    }
}
