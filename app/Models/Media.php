<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

/**
 * Modèle Media polymorphique pour fichiers et images.
 */
class Media extends Model
{
    use HasFactory;

    protected $table = 'medias'; // ✅ Spécifier le nom exact de la table
    protected $fillable = ['model_id','model_type','nom_fichier','chemin','type_mime','taille'];

    // Relation polymorphique
    public function model()
    {
        return $this->morphTo();
    }

    // URL publique via le disk configuré
    public function url(): string
    {
        return Storage::url($this->chemin);
    }

    // Supprime le fichier via le storage puis supprime l'enregistrement
    public function supprimerFichier(): bool
    {
        if (Storage::exists($this->chemin)) {
            Storage::delete($this->chemin);
        }
        return $this->delete();
    }
}
