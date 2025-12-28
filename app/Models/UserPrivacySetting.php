<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle UserPrivacySetting pour les paramètres de confidentialité
 */
class UserPrivacySetting extends Model
{
    use HasFactory;

    protected $table = 'user_privacy_settings';

    protected $fillable = [
        'utilisateur_id',
        'profil_visibilite',
        'messages_acceptes',
        'publications_lisibles',
        'commentaires_acceptes',
        'groupes_visibles',
        'afficher_contacts',
        'afficher_groupes',
        'afficher_activite',
        'mentions_autorisees',
        'notifier_requetes_contact',
        'notifier_commentaires',
        'notifier_reactions',
    ];

    protected $casts = [
        'afficher_contacts' => 'boolean',
        'afficher_groupes' => 'boolean',
        'afficher_activite' => 'boolean',
        'mentions_autorisees' => 'boolean',
        'notifier_requetes_contact' => 'boolean',
        'notifier_commentaires' => 'boolean',
        'notifier_reactions' => 'boolean',
    ];

    /**
     * Relation vers l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    /**
     * Vérifie si une action est autorisée selon les paramètres
     */
    public function canViewProfile(Utilisateur $viewer = null): bool
    {
        if ($this->profil_visibilite === 'public') {
            return true;
        }

        if ($this->profil_visibilite === 'prive') {
            return $viewer && $viewer->id === $this->utilisateur_id;
        }

        // 'amis' - À implémenter selon votre logique d'amis/contacts
        return $viewer && $viewer->id === $this->utilisateur_id;
    }

    /**
     * Vérifie si on peut envoyer un message à cet utilisateur
     */
    public function canReceiveMessage(Utilisateur $sender = null): bool
    {
        if ($this->messages_acceptes === 'tous') {
            return true;
        }

        if ($this->messages_acceptes === 'personne') {
            return false;
        }

        // 'amis' - À implémenter selon votre logique d'amis
        return true;
    }

    /**
     * Vérifie si les publications sont visibles
     */
    public function canViewPublications(Utilisateur $viewer = null): bool
    {
        if ($this->publications_lisibles === 'public') {
            return true;
        }

        if ($this->publications_lisibles === 'prive') {
            return $viewer && $viewer->id === $this->utilisateur_id;
        }

        // 'amis'
        return $viewer && $viewer->id === $this->utilisateur_id;
    }

    /**
     * Vérifie si on peut commenter les publications
     */
    public function canComment(Utilisateur $commenter = null): bool
    {
        if ($this->commentaires_acceptes === 'tous') {
            return true;
        }

        if ($this->commentaires_acceptes === 'personne') {
            return false;
        }

        // 'amis'
        return $commenter && $commenter->id === $this->utilisateur_id;
    }
}
