<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

// Modèle Message pour la messagerie
class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = ['conversation_id', 'expediteur_id', 'contenu', 'lu_at'];

    // Conversation liée
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    // Expéditeur
    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'expediteur_id');
    }

    // Médias attachés
    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }
}
