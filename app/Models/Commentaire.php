<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle Commentaire pour les commentaires hiérarchiques.
 */
class Commentaire extends Model
{
    use HasFactory;

    protected $fillable = ['publication_id','utilisateur_id','parent_id','contenu'];

    // Publication parent
    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    // Auteur du commentaire
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // Alias pour compatibilité
    public function user()
    {
        return $this->utilisateur();
    }

    // Réactions polymorphiques
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    // Médias attachés
    public function medias()
    {
        return $this->morphMany(Media::class, 'model');
    }

    // Parent (pour hiérarchie)
    public function parent()
    {
        return $this->belongsTo(Commentaire::class, 'parent_id');
    }

    // Enfants
    public function enfants()
    {
        return $this->hasMany(Commentaire::class, 'parent_id');
    }
}
