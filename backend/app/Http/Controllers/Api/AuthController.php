<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthRequest;
use App\Http\Resources\UtilisateurResource;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Contrôleur API pour l'authentification
class AuthController extends Controller
{
    // Enregistrement d'un nouvel utilisateur
    public function register(StoreAuthRequest $request)
    {
        $data = $request->validated();

        $utilisateur = Utilisateur::create([
            'nom' => $data['nom'],
            'email' => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'filiere' => $data['filiere'] ?? null,
            'annee_etude' => $data['annee_etude'] ?? null,
        ]);

        // Création d'un token API
        $token = $utilisateur->createToken('api_token')->plainTextToken;

        return response()->json([
            'utilisateur' => new UtilisateurResource($utilisateur),
            'token' => $token,
        ], 201);
    }

    // Login avec email et mot_de_passe
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required|string',
        ], [
            'email.required' => 'L\'email est requis.',
            'mot_de_passe.required' => 'Le mot de passe est requis.',
        ]);

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants invalides.'], 401);
        }

        $token = $utilisateur->createToken('api_token')->plainTextToken;

        return response()->json([
            'utilisateur' => new UtilisateurResource($utilisateur),
            'token' => $token,
        ]);
    }

    // Déconnexion : suppression des tokens de l'utilisateur connecté
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
        }
        return response()->json(['message' => 'Déconnecté avec succès.']);
    }

    // Rafraîchir le token : supprime anciens tokens et crée un nouveau
    public function refresh(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['message' => 'Non authentifié.'], 401);

        $user->tokens()->delete();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    // Récupère le profil de l'utilisateur authentifié
    public function me(Request $request)
    {
        return new UtilisateurResource($request->user());
    }
}
