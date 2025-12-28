<?php

namespace App\Services\Auth;

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthService
{
    /**
     * Enregistrer un nouvel utilisateur
     * 
     * @param array $data Les données de l'utilisateur
     * @return Utilisateur L'utilisateur créé
     * @throws Exception
     */
    public function register(array $data): Utilisateur
    {
        // Validation des données
        if (!isset($data['nom']) || !isset($data['email']) || !isset($data['password'])) {
            throw new Exception('Données d\'enregistrement invalides');
        }

        // Vérifier si l'email existe déjà
        if (Utilisateur::where('email', $data['email'])->exists()) {
            throw new Exception('Cet email est déjà utilisé');
        }

        // Créer l'utilisateur
        $user = Utilisateur::create([
            'nom' => $data['nom'],
            'email' => strtolower($data['email']),
            'mot_de_passe' => Hash::make($data['password']),
            'role_id' => $data['role_id'] ?? null,
            'filiere' => $data['filiere'] ?? null,
            'annee_etude' => $data['annee_etude'] ?? null,
            'email_verified_at' => now(), // Auto-vérifier l'email
        ]);

        return $user;
    }

    /**
     * Authentifier un utilisateur
     * 
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe
     * @return Utilisateur|null
     */
    public function authenticate(string $email, string $password): ?Utilisateur
    {
        $user = Utilisateur::where('email', strtolower($email))->first();

        if (!$user || !Hash::check($password, $user->mot_de_passe)) {
            return null;
        }

        return $user;
    }

    /**
     * Générer un token API pour l'utilisateur
     * 
     * @param Utilisateur $user
     * @param string $deviceName Nom du dispositif
     * @return string Token plain text
     */
    public function generateToken(Utilisateur $user, string $deviceName = 'api'): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }

    /**
     * Révoquer tous les tokens d'un utilisateur
     * 
     * @param Utilisateur $user
     * @return void
     */
    public function revokeAllTokens(Utilisateur $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Révoquer le token actuel
     * 
     * @param Utilisateur $user
     * @return bool
     */
    public function revokeCurrentToken(Utilisateur $user): bool
    {
        try {
            $token = $user->currentAccessToken();
            if ($token) {
                $token->revoke(); // Utiliser revoke() au lieu de delete()
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
