<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle GroupeSetting pour la gestion des paramètres des groupes.
 */
class GroupeSetting extends Model
{
    use HasFactory;

    protected $table = 'groupe_settings';
    protected $fillable = [
        'groupe_id',
        'moderation_requise',
        'autoriser_messages',
        'autoriser_publications',
        'autoriser_medias',
        'permission_publication',
        'permission_message',
        'mots_cles_interdits'
    ];

    protected $casts = [
        'moderation_requise' => 'boolean',
        'autoriser_messages' => 'boolean',
        'autoriser_publications' => 'boolean',
        'autoriser_medias' => 'boolean',
        'mots_cles_interdits' => 'array',
    ];

    // Relation vers le groupe
    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }
}
