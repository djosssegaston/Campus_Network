<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VÉRIFICATION DES COMPTES ADMIN ===" . PHP_EOL . PHP_EOL;

// Vérifier les rôles
echo "📋 RÔLES EXISTANTS:" . PHP_EOL;
$roles = \App\Models\Role::all();
if($roles->count() > 0) {
    foreach($roles as $role) {
        echo "  ✓ {$role->nom} (ID: {$role->id}, Slug: {$role->slug})" . PHP_EOL;
    }
} else {
    echo "  ⚠️ Aucun rôle trouvé" . PHP_EOL;
}

echo PHP_EOL;

// Vérifier les admins
echo "👤 COMPTES ADMINISTRATEUR:" . PHP_EOL;
$admins = \App\Models\Utilisateur::whereHas('role', function($q) {
    $q->where('slug', 'administrateur');
})->get();

if($admins->count() > 0) {
    foreach($admins as $admin) {
        echo "  ✓ {$admin->nom} - Email: {$admin->email} (ID: {$admin->id})" . PHP_EOL;
    }
} else {
    echo "  ❌ AUCUN COMPTE ADMIN TROUVÉ!" . PHP_EOL;
}

echo PHP_EOL;

// Total utilisateurs
echo "📊 STATISTIQUES:" . PHP_EOL;
echo "  Total utilisateurs: " . \App\Models\Utilisateur::count() . PHP_EOL;

// Afficher les utilisateurs test si disponibles
echo PHP_EOL . "🧪 UTILISATEURS DE TEST:" . PHP_EOL;
$users = \App\Models\Utilisateur::whereIn('email', [
    'admin@campus.test',
    'user@campus.test'
])->get();

if($users->count() > 0) {
    foreach($users as $user) {
        $role = $user->role ? $user->role->nom : 'Aucun rôle';
        echo "  ✓ {$user->nom} - {$user->email} (Rôle: {$role})" . PHP_EOL;
    }
} else {
    echo "  ⚠️ Aucun utilisateur de test trouvé" . PHP_EOL;
}
