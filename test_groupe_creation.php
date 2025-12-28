<?php

// Script de test pour la création de groupe

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Groupe;
use App\Models\Utilisateur;

echo "=== TEST CRÉATION GROUPE ===\n\n";

// Récupérer un utilisateur
$user = Utilisateur::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé!\n";
    exit;
}

echo "✅ Utilisateur: " . $user->nom . " (ID: " . $user->id . ")\n";

// Créer un groupe
$groupe = Groupe::create([
    'nom' => 'Test Groupe ' . time(),
    'description' => 'Ceci est un groupe de test créé à ' . now(),
    'visibilite' => 'public',
    'admin_id' => $user->id,
]);

echo "✅ Groupe créé: " . $groupe->nom . " (ID: " . $groupe->id . ")\n";

// Ajouter l'utilisateur au groupe
$groupe->utilisateurs()->attach($user->id, ['role' => 'admin']);
echo "✅ Utilisateur ajouté au groupe\n";

// Vérifier que le groupe est visible
$groupes = Groupe::all();
echo "✅ Nombre de groupes en BD: " . $groupes->count() . "\n";

// Lister les groupes
echo "\nGroupes existants:\n";
foreach ($groupes as $g) {
    echo "  - " . $g->nom . " (" . $g->utilisateurs->count() . " membres)\n";
}

echo "\n✅ TEST RÉUSSI!\n";
