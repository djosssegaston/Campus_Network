#!/usr/bin/env php
<?php

// Bootstrap Laravel
define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Configuration du super admin
$email = 'admin@campus.com';
$password = 'Admin123!';
$name = 'Administrateur Campus';

echo "\n╔════════════════════════════════════════════════════════════════════╗\n";
echo "║          CONFIGURATION DU COMPTE SUPER ADMIN                       ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n\n";

try {
    // Vérifier/créer l'utilisateur
    $user = Utilisateur::where('email', $email)->first();
    
    if (!$user) {
        echo "→ Création d'un nouvel utilisateur...\n";
        $user = Utilisateur::create([
            'nom' => $name,
            'email' => $email,
            'mot_de_passe' => $password,
            'email_verified_at' => now(),
        ]);
        echo "✓ Utilisateur créé avec succès\n\n";
    } else {
        echo "✓ Utilisateur existant: {$user->email}\n\n";
    }
    
    // Récupérer le rôle super_admin
    $role = Role::where('slug', 'super_admin')->first();
    
    if (!$role) {
        echo "✗ Erreur: Le rôle 'super_admin' n'existe pas\n";
        echo "→ Exécutez d'abord: php artisan db:seed --class=RolePermissionSeeder\n";
        exit(1);
    }
    
    // Assigner le rôle
    $user->update(['role_id' => $role->id]);
    
    echo "╔════════════════════════════════════════════════════════════════════╗\n";
    echo "║                  ✅ CONFIGURATION COMPLÉTÉE                        ║\n";
    echo "╚════════════════════════════════════════════════════════════════════╝\n\n";
    
    echo "📋 VOS IDENTIFIANTS DE CONNEXION:\n";
    echo "──────────────────────────────────────────────────────────────────────\n";
    echo "   Email:            $email\n";
    echo "   Mot de passe:     $password\n";
    echo "   Rôle:             Super Admin\n";
    echo "   Niveau:           10 (Maximum)\n";
    echo "   Permissions:      17/17 (Toutes)\n";
    echo "──────────────────────────────────────────────────────────────────────\n\n";
    
    echo "🌐 LIEN DE CONNEXION:\n";
    echo "   http://localhost:8000/login\n\n";
    
    echo "📝 INSTRUCTIONS:\n";
    echo "   1. Ouvrez votre navigateur\n";
    echo "   2. Allez à: http://localhost:8000/login\n";
    echo "   3. Entrez l'email et le mot de passe ci-dessus\n";
    echo "   4. Cliquez sur \"Se connecter\"\n\n";
    
    echo "✨ VOUS AUREZ ACCÈS À:\n";
    echo "   ✓ Tous les panneaux d'administration\n";
    echo "   ✓ Gestion des utilisateurs\n";
    echo "   ✓ Gestion des rôles et permissions\n";
    echo "   ✓ Modération du contenu\n";
    echo "   ✓ Statistiques et analyses\n\n";
    
} catch (\Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);
