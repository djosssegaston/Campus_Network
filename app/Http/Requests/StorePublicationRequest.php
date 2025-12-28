<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicationRequest extends FormRequest
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
            'titre' => 'nullable|string|max:255',
            'contenu' => 'required|string|min:5|max:5000',
            'groupe_id' => 'nullable|exists:groupes,id',
            'visibilite' => 'required|in:public,amis,prive',
            'medias' => 'nullable|array|max:10', // Max 10 fichiers
            'medias.*' => 'file|max:102400|mimes:jpeg,jpg,png,gif,webp,mp4,avi,mov,mkv,webm,mp3,wav,ogg,m4a,flac', // Max 100 MB par fichier
        ];
    }

    public function messages(): array
    {
        return [
            'contenu.required' => 'Le contenu est obligatoire',
            'contenu.min' => 'Le contenu doit contenir au moins 5 caractères',
            'contenu.max' => 'Le contenu ne peut pas dépasser 5000 caractères',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères',
            'groupe_id.exists' => 'Le groupe spécifié n\'existe pas',
            'visibilite.required' => 'La visibilité est obligatoire',
            'visibilite.in' => 'La visibilité doit être: public, amis ou prive',
            'medias.max' => 'Vous pouvez ajouter maximum 10 fichiers',
            'medias.*.file' => 'Un des fichiers n\'est pas valide',
            'medias.*.max' => 'Un fichier dépasse 100 MB',
            'medias.*.mimes' => 'Types acceptés: images (jpg, png, gif, webp), vidéos (mp4, avi, mov), sons (mp3, wav, ogg)',
        ];
    }
}
