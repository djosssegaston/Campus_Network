<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphMany};

// Modèle Publication représentant un post
class Publication extends Model
{
    use HasFactory;

    protected $table = 'publications';

    protected $fillable = ['utilisateur_id', 'groupe_id', 'contenu', 'visibilite', 'statut'];

    // Relation vers l'auteur
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // Relation vers le groupe éventuellement associé
    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }

    // Commentaires hiérarchiques
    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class, 'publication_id');
    }

    // Réactions polymorphiques
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    // Médias attachés
    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    // Détermine si l'utilisateur peut modifier la publication
    public function peutModifier(Utilisateur $utilisateur): bool
    {
        // L'auteur peut modifier pendant 24h. Les admins peuvent tout.
        if ($utilisateur->estAdmin()) return true;
        if ($this->utilisateur_id !== $utilisateur->id) return false;
        return $this->created_at->diffInHours(now()) <= 24;
    }

    // Vérifie si la publication est signalée
    public function estSignale(): bool
    {
        return $this->statut === 'signale';
    }
}
