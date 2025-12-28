<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Notification as Notifier;

/**
 * Modèle Notification (table notifications personnalisée).
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['utilisateur_id','type','donnees','read_at'];

    protected $casts = ['donnees' => 'array','read_at' => 'datetime'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // Send a notification record (helper)
    public static function envoyer(Utilisateur $utilisateur, string $type, array $donnees = [])
    {
        return self::create(['utilisateur_id' => $utilisateur->id, 'type' => $type, 'donnees' => $donnees]);
    }

    public function marquerCommeLue()
    {
        $this->read_at = now();
        return $this->save();
    }
}
