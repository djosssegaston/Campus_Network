<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Vérifier si l'utilisateur actuel a une permission
     */
    public static function hasPermission($permission)
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasPermission')) {
            return false;
        }
        
        return $user->hasPermission($permission);
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public static function isAdmin()
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'estAdmin')) {
            return false;
        }
        
        return $user->estAdmin();
    }

    /**
     * Vérifier si l'utilisateur est modérateur
     */
    public static function isModerator()
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'estModerateurGlobal')) {
            return false;
        }
        
        return $user->estModerateurGlobal();
    }

    /**
     * Récupérer le slug du rôle
     */
    public static function getRoleSlug()
    {
        $user = Auth::user();
        if (!$user || !$user->role) {
            return 'guest';
        }
        
        return $user->role->slug;
    }

    /**
     * Récupérer le nom du rôle
     */
    public static function getRoleName()
    {
        $user = Auth::user();
        if (!$user || !$user->role) {
            return 'Invité';
        }
        
        return $user->role->name;
    }

    /**
     * Vérifier si l'utilisateur peut éditer une ressource
     */
    public static function canEdit($ownerId)
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // L'utilisateur peut éditer s'il en est le propriétaire ou s'il est admin
        return $user->id === $ownerId || (method_exists($user, 'estAdmin') && $user->estAdmin());
    }

    /**
     * Vérifier si l'utilisateur peut supprimer une ressource
     */
    public static function canDelete($ownerId)
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // L'utilisateur peut supprimer s'il en est le propriétaire ou s'il est admin/modérateur
        return $user->id === $ownerId || 
               (method_exists($user, 'estAdmin') && $user->estAdmin()) || 
               (method_exists($user, 'estModerateurGlobal') && $user->estModerateurGlobal());
    }

    /**
     * Vérifier si l'utilisateur peut modérer
     */
    public static function canModerate()
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasPermission')) {
            return false;
        }
        
        return $user->hasPermission('moderate_content');
    }

    /**
     * Vérifier si l'utilisateur peut gérer les rôles
     */
    public static function canManageRoles()
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasPermission')) {
            return false;
        }
        
        return $user->hasPermission('manage_roles');
    }

    /**
     * Vérifier si l'utilisateur peut gérer les utilisateurs
     */
    public static function canManageUsers()
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'estAdmin')) {
            return false;
        }
        
        return $user->estAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut bannir
     */
    public static function canBan()
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasPermission')) {
            return false;
        }
        
        return $user->hasPermission('ban_user');
    }

    /**
     * Récupérer le nom d'affichage du rôle
     */
    public static function getRoleDisplayName($role = null)
    {
        if (!$role) {
            $user = Auth::user();
            if (!$user || !$user->role) {
                return 'Invité';
            }
            $role = $user->role;
        }

        // Mapper les slugs aux noms d'affichage français
        $displayNames = [
            'etudiant' => 'Étudiant',
            'moderateur_groupe' => 'Modérateur Groupe',
            'admin_groupe' => 'Admin Groupe',
            'moderateur_global' => 'Modérateur Global',
            'administrateur' => 'Administrateur',
            'super_admin' => 'Super Admin',
        ];

        return $displayNames[$role->slug] ?? $role->name ?? 'Invité';
    }
}
