<?php

namespace App\Policies;

use App\Models\Utilisateur;

class UserPolicy
{
    public function viewAny(Utilisateur $user): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function view(Utilisateur $user, Utilisateur $utilisateur): bool
    {
        return $user->role?->slug === 'admin' || $user->id === $utilisateur->id;
    }

    public function create(Utilisateur $user): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function update(Utilisateur $user, Utilisateur $utilisateur): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function delete(Utilisateur $user, Utilisateur $utilisateur): bool
    {
        return $user->role?->slug === 'admin' && $user->id !== $utilisateur->id;
    }
}
