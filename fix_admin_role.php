<?php
/**
 * Script de correction des rôles administrateurs
 * Supprime les doublons et normalise les slugs
 */

// Charger Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\Utilisateur;

echo "=== FIX ADMIN ROLE ===\n\n";

// 1. Afficher tous les rôles actuels
echo "📋 Rôles actuels dans la base de données:\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "  - {$role->nom} (slug: {$role->slug}, niveau: {$role->niveau})\n";
}

// 2. Chercher les utilisateurs avec le rôle "administrateur"
$usersWithAdministrateur = Utilisateur::whereHas('role', function($q) {
    $q->where('slug', 'administrateur');
})->get();

if ($usersWithAdministrateur->count() > 0) {
    echo "\n⚠️  Utilisateurs avec le rôle 'administrateur':\n";
    foreach ($usersWithAdministrateur as $user) {
        echo "  - {$user->nom} ({$user->email})\n";
    }
    
    // Trouver le rôle "admin"
    $adminRole = Role::where('slug', 'admin')->first();
    if ($adminRole) {
        echo "\n✅ Migration des utilisateurs vers le rôle 'admin'...\n";
        foreach ($usersWithAdministrateur as $user) {
            $user->update(['role_id' => $adminRole->id]);
            echo "  ✓ {$user->nom} migré\n";
        }
    }
}

// 3. Supprimer le rôle "administrateur" s'il existe et n'a plus d'utilisateurs
$roleAdministrateur = Role::where('slug', 'administrateur')->first();
if ($roleAdministrateur) {
    $count = $roleAdministrateur->utilisateurs()->count();
    if ($count === 0) {
        echo "\n🗑️  Suppression du rôle dupliqué 'administrateur'...\n";
        $roleAdministrateur->permissions()->detach();
        $roleAdministrateur->delete();
        echo "  ✓ Rôle supprimé\n";
    }
}

// 4. Afficher l'état final
echo "\n✅ État final des rôles:\n";
$roles = Role::all();
foreach ($roles as $role) {
    $count = $role->utilisateurs()->count();
    echo "  - {$role->nom} (slug: {$role->slug}): {$count} utilisateur(s)\n";
}

echo "\n=== FIX COMPLÉTÉE ===\n";
