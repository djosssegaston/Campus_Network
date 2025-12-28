<?php

namespace App\Policies;

use App\Models\Utilisateur;
use App\Models\Role;

class RolePolicy
{
    public function viewAnyRole(Utilisateur $user): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function createRole(Utilisateur $user): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function updateRole(Utilisateur $user, Role $role): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function deleteRole(Utilisateur $user, Role $role): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function viewAnyPermission(Utilisateur $user): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function createPermission(Utilisateur $user): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function updatePermission(Utilisateur $user): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function deletePermission(Utilisateur $user): bool
    {
        return $user->role?->slug === 'admin';
    }
}
