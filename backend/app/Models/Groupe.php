<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany};

// Modèle Groupe représentant une communauté
class Groupe extends Model
{
    use HasFactory;

    protected $table = 'groupes';

    protected $fillable = ['nom', 'description', 'visibilite', 'categorie', 'regles', 'admin_id'];

    protected $casts = [
        'regles' => 'array',
    ];

    // Admin du groupe
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'admin_id');
    }

    // Publications du groupe
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, 'groupe_id');
    }

    // Membres via pivot
    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(Utilisateur::class, 'groupe_utilisateurs', 'groupe_id', 'utilisateur_id')
            ->withPivot('role', 'rejoins_at')
            ->withTimestamps();
    }

    // Moderateurs (filtre pivot)
    public function moderateurs()
    {
        return $this->membres()->wherePivot('role', 'moderateur');
    }

    // Evenements du groupe
    public function evenements(): HasMany
    {
        return $this->hasMany(EvenementGroupe::class, 'groupe_id');
    }
}
