<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UserAuthResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * Créer une nouvelle instance du contrôleur.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Enregistrer un nouvel utilisateur
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Enregistrer l'utilisateur
            $user = $this->authService->register($request->validated());

            // Générer le token
            $token = $this->authService->generateToken($user);

            return response()->json([
                'message' => 'Inscription réussie',
                'user' => new UserAuthResource($user),
                'token' => $token,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'inscription',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Authentifier un utilisateur
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Authentifier l'utilisateur
            $user = $this->authService->authenticate(
                $request->validated('email'),
                $request->validated('password')
            );

            if (!$user) {
                return response()->json([
                    'message' => 'Email ou mot de passe incorrect',
                ], 401);
            }

            // Générer le token
            $token = $this->authService->generateToken($user);

            return response()->json([
                'message' => 'Connexion réussie',
                'user' => new UserAuthResource($user),
                'token' => $token,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Déconnecter l'utilisateur actuel
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->revokeCurrentToken($request->user());

            return response()->json([
                'message' => 'Déconnexion réussie',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la déconnexion',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Déconnecter l'utilisateur de tous les appareils
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            $this->authService->revokeAllTokens($request->user());

            return response()->json([
                'message' => 'Déconnecté de tous les appareils',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la déconnexion',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Obtenir les informations de l'utilisateur actuel
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserAuthResource($request->user()),
        ], 200);
    }
}
