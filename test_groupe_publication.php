#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Utilisateur;
use App\Models\Groupe;

echo "=== TEST GROUPE PUBLICATION ===\n\n";

// Trouver un utilisateur régulier
$user = Utilisateur::where('email', 'test@example.com')->orWhere('email', 'adechina@example.com')->first() 
    ?? Utilisateur::whereNotNull('role_id')->first();

if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit;
}

echo "✅ Utilisateur trouvé: {$user->nom} ({$user->email})\n";
echo "   ID: {$user->id}\n";
echo "   Rôle: " . ($user->role ? $user->role->nom : 'Pas de rôle') . "\n\n";

// Trouver un groupe
$groupe = Groupe::first();

if (!$groupe) {
    echo "❌ Aucun groupe trouvé\n";
    exit;
}

echo "✅ Groupe trouvé: {$groupe->nom} (ID: {$groupe->id})\n";
echo "   Admin: " . ($groupe->admin ? $groupe->admin->nom : 'Pas d\'admin') . "\n";

// Vérifier si l'utilisateur est membre
$isMember = $groupe->utilisateurs->contains($user);
echo "   L'utilisateur est membre: " . ($isMember ? '✅ OUI' : '❌ NON') . "\n";

// Si pas membre, l'ajouter
if (!$isMember) {
    echo "\n➕ Ajout de l'utilisateur au groupe...\n";
    $groupe->utilisateurs()->attach($user->id, ['role' => 'membre']);
    echo "   ✅ Utilisateur ajouté\n";
}

// Vérifier les paramètres du groupe
$settings = $groupe->getSettings();
echo "\n📋 Paramètres du groupe:\n";
echo "   Autoriser publications: " . ($settings->autoriser_publications ? '✅' : '❌') . "\n";
echo "   Autoriser messages: " . ($settings->autoriser_messages ? '✅' : '❌') . "\n";
echo "   Autoriser médias: " . ($settings->autoriser_medias ? '✅' : '❌') . "\n";
echo "   Permission publication: {$settings->permission_publication}\n";

echo "\n=== FIN ===\n";
