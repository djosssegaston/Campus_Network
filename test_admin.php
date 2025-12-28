#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Utilisateur;

echo "=== TEST ADMIN ACCESS ===\n\n";

// Trouver l'admin
$admin = Utilisateur::whereHas('role', function($q) {
    $q->where('slug', 'admin');
})->first();

if ($admin) {
    echo "✅ Admin trouvé:\n";
    echo "   Nom: {$admin->nom}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Rôle: {$admin->role->nom} (slug: {$admin->role->slug})\n";
    echo "   estAdmin(): " . ($admin->estAdmin() ? '✅ TRUE' : '❌ FALSE') . "\n";
    echo "   hasPermission('manage_roles'): " . ($admin->hasPermission('manage_roles') ? '✅ TRUE' : '❌ FALSE') . "\n";
} else {
    echo "❌ Aucun administrateur trouvé!\n";
}

echo "\n=== TOUS LES UTILISATEURS ===\n";
$users = Utilisateur::with('role')->get();
foreach ($users as $user) {
    echo "- {$user->nom} -> " . ($user->role ? $user->role->nom : 'Pas de rôle') . "\n";
}
