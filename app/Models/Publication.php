<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Publication représentant une publication dans le fil.
 */
class Publication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['utilisateur_id','groupe_id','contenu','visibilite','statut'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relation vers l'utilisateur auteur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // Alias pour compatibilité
    public function user()
    {
        return $this->utilisateur();
    }

    // Relation vers le groupe (optionnel)
    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }

    // Commentaires (hiérarchiques gérés par la table commentaires)
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'publication_id');
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

    // Partages de la publication
    public function partages()
    {
        return $this->hasMany(Partage::class, 'publication_id');
    }

    // Vérifie si un utilisateur peut modifier (dans les 24h ou admin)
    public function peutModifier(Utilisateur $utilisateur): bool
    {
        if ($utilisateur->estAdmin()) return true;
        if ($this->utilisateur_id !== $utilisateur->id) return false;
        return $this->created_at->gt(now()->subHours(24));
    }

    // Indique si la publication est signalée
    public function estSignale(): bool
    {
        return $this->statut === 'signale';
    }
}
