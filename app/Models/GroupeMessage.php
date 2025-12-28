<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle GroupeMessage représentant un message dans un groupe.
 */
class GroupeMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'groupe_messages';
    protected $fillable = ['groupe_id', 'utilisateur_id', 'contenu', 'type'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relation vers le groupe
    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    // Relation vers l'utilisateur auteur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    // Alias pour compatibilité
    public function user()
    {
        return $this->utilisateur();
    }

    // Médias attachés
    public function medias()
    {
        return $this->morphMany(Media::class, 'model');
    }

    // Vérifie si c'est un message multimédia
    public function isMedia(): bool
    {
        return in_array($this->type, ['image', 'video', 'audio', 'fichier']);
    }
}
