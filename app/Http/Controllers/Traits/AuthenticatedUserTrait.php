<?php

namespace App\Http\Controllers\Traits;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

trait AuthenticatedUserTrait
{
    /**
     * Obtenir l'utilisateur authentifié actuel
     *
     * @param Request $request
     * @return Utilisateur|null
     */
    public function getAuthenticatedUser(Request $request): ?Utilisateur
    {
        return $request->user();
    }

    /**
     * Obtenir l'ID de l'utilisateur authentifié actuel
     *
     * @param Request $request
     * @return int|null
     */
    public function getAuthenticatedUserId(Request $request): ?int
    {
        return $request->user()?->id;
    }

    /**
     * Vérifier si l'utilisateur est authentifié
     *
     * @param Request $request
     * @return bool
     */
    public function isAuthenticated(Request $request): bool
    {
        return $request->user() !== null;
    }

    /**
     * Autoriser une action basée sur l'utilisateur
     *
     * @param Request $request
     * @param int $userId
     * @return bool
     */
    public function isAuthorized(Request $request, int $userId): bool
    {
        $user = $this->getAuthenticatedUser($request);
        return $user && ($user->id === $userId || $user->estAdmin());
    }
}
