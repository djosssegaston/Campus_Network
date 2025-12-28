<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle Partage représentant un partage de publication.
 */
class Partage extends Model
{
    use HasFactory;

    protected $fillable = ['utilisateur_id', 'publication_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // L'utilisateur qui a partagé
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // La publication partagée
    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }
}
