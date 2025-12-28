<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modèle Role pour gérer les rôles et permissions.
 */
class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $fillable = ['nom', 'description'];
    public $timestamps = true;

    // Constantes de rôles
    public const ETUDIANT = 'etudiant';
    public const MODERATEUR_GROUPE = 'moderateur_groupe';
    public const ADMIN_GROUPE = 'admin_groupe';
    public const MODERATEUR_GLOBAL = 'moderateur_global';
    public const ADMINISTRATEUR = 'administrateur';
    public const SUPER_ADMIN = 'super_admin';

    /**
     * Utilisateurs associés
     */
    public function utilisateurs(): HasMany
    {
        return $this->hasMany(Utilisateur::class, 'role_id');
    }

    /**
     * Permissions associées au rôle
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Vérifier si le rôle a une permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()
            ->where('nom', $permission)
            ->exists();
    }

    /**
     * Vérifier si c'est un rôle administrateur
     */
    public function isAdmin(): bool
    {
        return in_array($this->slug, ['administrateur', 'super_admin', 'admin']);
    }

    /**
     * Vérifier si c'est un rôle modérateur
     */
    public function isModerator(): bool
    {
        return in_array($this->slug, ['moderateur_global', 'moderateur_groupe', 'admin_groupe']);
    }

    /**
     * Vérifier la hiérarchie - si ce rôle est >= au rôle donné
     */
    public function isHigherThan(Role $otherRole): bool
    {
        return $this->niveau >= $otherRole->niveau;
    }

    /**
     * Obtenir toutes les permissions du rôle
     */
    public function getAllPermissions()
    {
        return $this->permissions()->pluck('nom')->toArray();
    }
}
