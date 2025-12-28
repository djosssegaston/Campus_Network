<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphMany};

// Modèle Commentaire pour commentaires hiérarchiques
class Commentaire extends Model
{
    use HasFactory;

    protected $table = 'commentaires';

    protected $fillable = ['publication_id', 'utilisateur_id', 'contenu', 'parent_id', 'statut'];

    // Relation vers la publication
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    // Auteur du commentaire
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
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

    // Relation parent (auto-référence)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Enfants (réponses)
    public function enfants(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
