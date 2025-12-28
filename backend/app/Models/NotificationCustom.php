<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Modèle NotificationCustom pour notifications JSON simples
class NotificationCustom extends Model
{
    use HasFactory;

    protected $table = 'notifications_custom';

    protected $fillable = ['utilisateur_id', 'type', 'donnees', 'lu_at'];

    protected $casts = [
        'donnees' => 'array',
        'lu_at' => 'datetime',
    ];

    // Utilisateur destinataire
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // Envoie une notification (prototype)
    public static function envoyer(Utilisateur $utilisateur, string $type, array $donnees = [])
    {
        return self::create([
            'utilisateur_id' => $utilisateur->id,
            'type' => $type,
            'donnees' => $donnees,
        ]);
    }

    // Marque comme lue
    public function marquerCommeLue(): bool
    {
        $this->lu_at = now();
        return $this->save();
    }
}
