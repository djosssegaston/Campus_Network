<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsToMany, HasManyThrough, BelongsTo};

// Modèle Utilisateur principal
class Utilisateur extends AuthenticatableUser implements MustVerifyEmail
{
    // Active les tokens API via Sanctum
    use HasApiTokens, HasFactory, Notifiable;

    // Nom de la table personnalisé
    protected $table = 'utilisateurs';

    // Champs remplissables
    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'filiere',
        'annee_etude',
        'avatar_url',
        'role_id',
    ];

    // Champs cachés pour les sérialisations
    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    // Casts
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Mutateur pour hacher le mot de passe lors de l'affectation
    public function setMotDePasseAttribute($value)
    {
        if (!$value) {
            $this->attributes['mot_de_passe'] = $value;
            return;
        }

        // If the value already looks hashed, don't re-hash
        if (Hash::needsRehash($value) === false) {
            $this->attributes['mot_de_passe'] = $value;
        } else {
            $this->attributes['mot_de_passe'] = Hash::make($value);
        }
    }

    // Retourne le champ utilisé pour l'authentification
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // Relations : publications de l'utilisateur
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, 'utilisateur_id');
    }

    // Relations : commentaires
    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class, 'utilisateur_id');
    }

    // Relations : réactions
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class, 'utilisateur_id');
    }

    // Relations : groupes (pivot)
    public function groupes(): BelongsToMany
    {
        return $this->belongsToMany(Groupe::class, 'groupe_utilisateurs', 'utilisateur_id', 'groupe_id')
            ->withPivot('role', 'rejoins_at')
            ->withTimestamps();
    }

    // Relations : messages envoyés
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    // Relations : notifications personnalisées
    public function notificationsCustom(): HasMany
    {
        return $this->hasMany(NotificationCustom::class, 'utilisateur_id');
    }

    // Vérifie si l'utilisateur est administrateur global
    public function estAdmin(): bool
    {
        if ($this->role) {
            return in_array($this->role->slug, ['administrateur', 'super_admin']);
        }
        return false;
    }

    // Vérifie si l'utilisateur est modérateur d'un groupe donné
    public function estModerateurDeGroupe(Groupe $groupe): bool
    {
        $pivot = $this->groupes()->where('groupe_id', $groupe->id)->first();
        if (!$pivot) return false;
        return $pivot->pivot->role === 'moderateur' || $pivot->pivot->role === 'admin';
    }

    // Groupes administrés par l'utilisateur
    public function groupesAdministres()
    {
        return $this->hasMany(Groupe::class, 'admin_id');
    }

    // Role relation
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
