<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'recipient_id' => ['required', 'exists:utilisateurs,id', 'integer', function ($attribute, $value, $fail) {
                if ($value == auth()->id()) {
                    $fail('Vous ne pouvez pas vous envoyer de message.');
                }
            }],
            'contenu' => 'required|string|min:1|max:5000',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'recipient_id.required' => 'Le destinataire est requis',
            'recipient_id.exists' => 'Le destinataire n\'existe pas',
            'recipient_id.different' => 'Vous ne pouvez pas vous envoyer de message',
            'contenu.required' => 'Le contenu du message est requis',
            'contenu.min' => 'Le message doit contenir au moins 1 caractère',
            'contenu.max' => 'Le message ne doit pas dépasser 5000 caractères',
        ];
    }
}
