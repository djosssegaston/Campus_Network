<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle Reaction polymorphique.
 */
class Reaction extends Model
{
    use HasFactory;

    protected $fillable = ['utilisateur_id','type','reactable_id','reactable_type'];

    // Utilisateur auteur de la réaction
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // Alias pour compatibilité
    public function user()
    {
        return $this->utilisateur();
    }

    // Relation polymorphique vers le contenu (publication, commentaire...)
    public function reactable()
    {
        return $this->morphTo();
    }
}
