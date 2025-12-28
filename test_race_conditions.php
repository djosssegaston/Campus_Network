<?php

/**
 * Test Script - Race Condition et Concurrent Requests
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Conversation;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "===== TEST RACE CONDITIONS =====\n\n";

// Get users
$user1 = Utilisateur::where('id', '!=', 1)->first();
$user2 = Utilisateur::where('id', '!=', 1)->where('id', '!=', $user1?->id)->first();
$user3 = Utilisateur::where('id', '!=', 1)->where('id', '!=', $user1?->id)->where('id', '!=', $user2?->id)->first();

if (!$user1 || !$user2 || !$user3) {
    echo "❌ Besoin de 3 utilisateurs!\n";
    exit;
}

echo "User 1: {$user1->nom} (ID: {$user1->id})\n";
echo "User 2: {$user2->nom} (ID: {$user2->id})\n";
echo "User 3: {$user3->nom} (ID: {$user3->id})\n\n";

// Test 1: Rapid sequential creation attempts
echo "--- TEST 1: Tentatives rapides de création (sans transaction) ---\n";

// Clear any existing conversation between user1 and user2
$existing = Conversation::whereHas('utilisateurs', function ($q) use ($user1, $user2) {
    $q->where('utilisateur_id', $user1->id);
})->whereHas('utilisateurs', function ($q) use ($user1, $user2) {
    $q->where('utilisateur_id', $user2->id);
})->first();

if ($existing) {
    $existing->utilisateurs()->detach();
    $existing->delete();
    echo "✅ Conversation existante supprimée pour ce test\n";
}

Auth::setUser($user1);

// Attempt 1
echo "Tentative 1...\n";
$conv1 = Conversation::create();
$conv1->utilisateurs()->attach([$user1->id, $user2->id]);
echo "✅ Conv 1 créée (ID: {$conv1->id}), utilisateurs: {$conv1->utilisateurs()->count()}\n";

// Attempt 2 - Check if exists before creating
echo "Tentative 2...\n";
$existing2 = Conversation::whereHas('utilisateurs', function ($q) use ($user2) {
    $q->where('utilisateur_id', $user2->id);
})->whereHas('utilisateurs', function ($q) use ($user1) {
    $q->where('utilisateur_id', $user1->id);
})->first();

if ($existing2) {
    echo "✅ Conversation trouvée (ID: {$existing2->id})\n";
} else {
    echo "❌ Conversation non trouvée!\n";
}

// Test 2: Transaction-based safety
echo "\n--- TEST 2: Tentatives avec transaction ---\n";

// Clear
$existing = Conversation::whereHas('utilisateurs', function ($q) use ($user1, $user3) {
    $q->where('utilisateur_id', $user1->id);
})->whereHas('utilisateurs', function ($q) use ($user1, $user3) {
    $q->where('utilisateur_id', $user3->id);
})->first();

if ($existing) {
    $existing->utilisateurs()->detach();
    $existing->delete();
}

try {
    $conv2 = DB::transaction(function () use ($user1, $user3) {
        $c = Conversation::create();
        $c->utilisateurs()->attach([$user1->id, $user3->id]);
        return $c;
    });
    
    echo "✅ Conv avec transaction créée (ID: {$conv2->id})\n";
    echo "✅ Utilisateurs attachés: {$conv2->utilisateurs()->count()}\n";
    echo "✅ TEST 2 PASSED\n";
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
}

// Test 3: Duplicate key prevention
echo "\n--- TEST 3: Prévention des doublons (contrainte unique) ---\n";

try {
    // Try to attach same user twice
    $conv1->utilisateurs()->attach([$user1->id]);
    echo "❌ Aucune contrainte unique détectée!\n";
} catch (\Illuminate\Database\QueryException $e) {
    if (strpos($e->getMessage(), 'Duplicate') !== false || strpos($e->getMessage(), 'unique') !== false) {
        echo "✅ Contrainte unique fonctionne correctement\n";
        echo "   Erreur: " . $e->getMessage() . "\n";
    } else {
        echo "⚠️  Erreur database: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ TOUS LES TESTS COMPLÉTÉS\n";
