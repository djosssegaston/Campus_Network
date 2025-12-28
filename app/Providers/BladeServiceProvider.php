<?php

/**
 * Fonctions Blade Helpers pour Permissions & Rôles
 * 
 * Utilisez dans les templates Blade:
 * @canPerm('permission_name')
 *   // Contenu visible si permission
 * @endcanPerm
 * 
 * @isRole('role_slug')
 *   // Contenu si utilisateur a ce rôle
 * @endisRole
 */

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

// @canPerm('permission') ... @endcanPerm
Blade::if('canPerm', function ($permission) {
    $user = Auth::user();
    return $user && $user->hasPermission($permission);
});

// @isAdmin ... @endisAdmin
Blade::if('isAdmin', function () {
    $user = Auth::user();
    return $user && $user->estAdmin();
});

// @isMod ... @endisMod
Blade::if('isMod', function () {
    $user = Auth::user();
    return $user && $user->estModerateurGlobal();
});

// @isRole('role_slug') ... @endisRole
Blade::if('isRole', function ($roleSlug) {
    $user = Auth::user();
    return $user && $user->role && $user->role->slug === $roleSlug;
});

// @canEdit($userId) ... @endcanEdit
Blade::if('canEdit', function ($userId) {
    $user = Auth::user();
    return $user && ($user->id === $userId || $user->estAdmin());
});

// @canDelete($userId) ... @endcanDelete
Blade::if('canDelete', function ($userId) {
    $user = Auth::user();
    return $user && ($user->id === $userId || $user->estAdmin() || $user->estModerateurGlobal());
});

// @hasRole('role_name') ... @endhasRole
Blade::if('hasRole', function ($roleName) {
    $user = Auth::user();
    return $user && $user->role && $user->role->name === $roleName;
});
