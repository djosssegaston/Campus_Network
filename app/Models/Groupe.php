<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle Groupe représentant une communauté.
 */
class Groupe extends Model
{
    use HasFactory;

    protected $fillable = ['nom','description','visibilite','categorie','regles','admin_id'];

    protected $casts = [
        'regles' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Admin du groupe
    public function admin()
    {
        return $this->belongsTo(Utilisateur::class, 'admin_id');
    }

    // Publications du groupe
    public function publications()
    {
        return $this->hasMany(Publication::class, 'groupe_id');
    }

    // Membres via pivot - ATTENTION: nom de table corrects
    public function utilisateurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'groupe_utilisateurs', 'groupe_id', 'utilisateur_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Modérateurs (filtrés)
    public function moderateurs()
    {
        return $this->utilisateurs()->wherePivot('role','moderateur');
    }

    // Messages du groupe
    public function messages()
    {
        return $this->hasMany(GroupeMessage::class, 'groupe_id');
    }

    // Paramètres du groupe
    public function settings()
    {
        return $this->hasOne(GroupeSetting::class, 'groupe_id');
    }

    // Récupère ou crée les paramètres par défaut
    public function getSettings()
    {
        return $this->settings ?? GroupeSetting::firstOrCreate(
            ['groupe_id' => $this->id],
            [
                'moderation_requise' => false,
                'autoriser_messages' => true,
                'autoriser_publications' => true,
                'autoriser_medias' => true,
                'permission_publication' => 'tous',
                'permission_message' => 'tous',
            ]
        );
    }

    /**
     * Vérifier si un utilisateur est membre du groupe
     * Utilise une requête au lieu de charger la collection entière
     */
    public function hasMember(Utilisateur $user): bool
    {
        return $this->utilisateurs()
            ->where('utilisateur_id', $user->id)
            ->exists();
    }
}
