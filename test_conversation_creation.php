<?php

/**
 * Test Script - Création de Conversations
 * Vérifie que les conversations peuvent être créées correctement
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\DB;

echo "===== TEST CRÉATION DE CONVERSATIONS =====\n\n";

// Get 2 users
$user1 = Utilisateur::where('id', '!=', 1)->first();
$user2 = Utilisateur::where('id', '!=', 1)->where('id', '!=', $user1?->id)->first();

if (!$user1 || !$user2) {
    echo "❌ Besoin de 2 utilisateurs différents!\n";
    exit;
}

echo "✅ User 1: {$user1->nom} (ID: {$user1->id})\n";
echo "✅ User 2: {$user2->nom} (ID: {$user2->id})\n\n";

// Test 1: Basic conversation creation
echo "--- TEST 1: Création basique de conversation ---\n";
try {
    $conversation = Conversation::create();
    echo "✅ Conversation créée (ID: {$conversation->id})\n";
    
    $conversation->utilisateurs()->attach([$user1->id, $user2->id]);
    $count = $conversation->utilisateurs()->count();
    echo "✅ Utilisateurs attachés: {$count}\n";
    
    if ($count === 2) {
        echo "✅ TEST 1 PASSED\n\n";
    } else {
        echo "❌ TEST 1 FAILED - Seuls {$count} utilisateurs attachés au lieu de 2\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n\n";
}

// Test 2: Transaction-based creation
echo "--- TEST 2: Création avec transaction ---\n";
try {
    $conversation2 = DB::transaction(function () use ($user1, $user2) {
        $conv = Conversation::create();
        $conv->utilisateurs()->attach([$user1->id, $user2->id]);
        return $conv;
    });
    
    $count = $conversation2->utilisateurs()->count();
    echo "✅ Conversation créée avec transaction (ID: {$conversation2->id})\n";
    echo "✅ Utilisateurs attachés: {$count}\n";
    
    if ($count === 2) {
        echo "✅ TEST 2 PASSED\n\n";
    } else {
        echo "❌ TEST 2 FAILED - Seuls {$count} utilisateurs attachés\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n\n";
}

// Test 3: Check for duplicates and existence
echo "--- TEST 3: Vérifier les doublons ---\n";
$existingConv = Conversation::whereHas('utilisateurs', function ($query) use ($user1) {
    $query->where('utilisateur_id', $user1->id);
})->whereHas('utilisateurs', function ($query) use ($user2) {
    $query->where('utilisateur_id', $user2->id);
})->first();

if ($existingConv) {
    echo "✅ Conversation existante trouvée (ID: {$existingConv->id})\n";
    echo "✅ TEST 3 PASSED\n\n";
} else {
    echo "❌ Aucune conversation trouvée entre les deux utilisateurs\n\n";
}

// Test 4: Message creation
echo "--- TEST 4: Créer un message dans la conversation ---\n";
if ($existingConv) {
    try {
        $message = $existingConv->messages()->create([
            'expediteur_id' => $user1->id,
            'contenu' => 'Message de test - ' . now(),
        ]);
        
        echo "✅ Message créé (ID: {$message->id})\n";
        echo "✅ Contenu: {$message->contenu}\n";
        echo "✅ TEST 4 PASSED\n\n";
    } catch (\Exception $e) {
        echo "❌ Erreur lors de la création du message: {$e->getMessage()}\n\n";
    }
}

// Summary
echo "===== RÉSUMÉ =====\n";
echo "✅ Tous les tests ont été exécutés\n";
echo "✅ Vérifiez les résultats ci-dessus pour les détails\n";
