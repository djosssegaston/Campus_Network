<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphTo;

// Modèle Media polymorphique pour fichiers et images
class Media extends Model
{
    use HasFactory;

    protected $table = 'medias';

    protected $fillable = ['model_id', 'model_type', 'nom_fichier', 'chemin', 'type_mime', 'taille'];

    // Relation polymorphique inverse
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    // Retourne l'URL publique du média
    public function url(): ?string
    {
        if (!$this->chemin) return null;
        return Storage::url($this->chemin);
    }

    // Supprime le fichier du disque et l'enregistrement
    public function supprimerFichier(): bool
    {
        if ($this->chemin && Storage::exists($this->chemin)) {
            Storage::delete($this->chemin);
        }
        return $this->delete();
    }
}
