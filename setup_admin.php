<?php

// Fichier temporaire pour configurer le super admin
// Exécutez-le avec: php setup_admin.php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Utilisateur;
use App\Models\Role;

echo "\n" . str_repeat('=', 70) . "\n";
echo "CONFIGURATION DU SUPER ADMIN\n";
echo str_repeat('=', 70) . "\n\n";

// Identifiants par défaut
$email = 'admin@campus.com';
$password = 'Admin123!';
$name = 'Administrateur Campus';

// Créer ou récupérer l'utilisateur
$user = Utilisateur::where('email', $email)->first();

if ($user) {
    echo "✓ Utilisateur trouvé: {$user->email}\n";
} else {
    echo "✓ Création d'un nouvel utilisateur...\n";
    $user = Utilisateur::create([
        'nom' => $name,
        'email' => $email,
        'mot_de_passe' => $password,
        'email_verified_at' => now(),
    ]);
    echo "✓ Utilisateur créé: {$user->email}\n";
}

// Assigner le rôle super_admin
$superAdminRole = Role::where('slug', 'super_admin')->first();

if (!$superAdminRole) {
    echo "✗ Rôle 'super_admin' non trouvé!\n";
    echo "Exécutez d'abord: php artisan db:seed --class=RolePermissionSeeder\n";
    exit(1);
}

$user->role_id = $superAdminRole->id;
$user->save();

echo "\n" . str_repeat('-', 70) . "\n";
echo "✅ SUPER ADMIN CONFIGURÉ AVEC SUCCÈS!\n";
echo str_repeat('-', 70) . "\n\n";

echo "📋 IDENTIFIANTS DE CONNEXION:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Email:            {$email}\n";
echo "Mot de passe:     {$password}\n";
echo "Rôle:             Super Admin (niveau 10)\n";
echo "Permissions:      TOUTES (17/17)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "\n🌐 URL DE CONNEXION:\n";
echo "http://localhost:8000/login\n";

echo "\n✨ PROCHAINES ÉTAPES:\n";
echo "1. Ouvrez http://localhost:8000/login\n";
echo "2. Entrez votre email et mot de passe\n";
echo "3. Vous aurez accès à toutes les fonctionnalités admin\n";

echo "\n" . str_repeat('=', 70) . "\n\n";
