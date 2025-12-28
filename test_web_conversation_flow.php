<?php

/**
 * Test Script - Simulation de création de conversation via formulaire web
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Conversation;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;

echo "===== TEST SIMULATION WEB - CRÉATION DE CONVERSATION =====\n\n";

// Get users
$user1 = Utilisateur::where('id', '!=', 1)->first();
$user2 = Utilisateur::where('id', '!=', 1)->where('id', '!=', $user1?->id)->first();

if (!$user1 || !$user2) {
    echo "❌ Besoin de 2 utilisateurs différents!\n";
    exit;
}

echo "User 1: {$user1->nom} (ID: {$user1->id})\n";
echo "User 2: {$user2->nom} (ID: {$user2->id})\n\n";

// Simulate authentication as user1
Auth::setUser($user1);

echo "--- Simulation: Utilisateur {$user1->nom} crée une conversation avec {$user2->nom} ---\n";

try {
    // Check if existing conversation
    $existing = Conversation::whereHas('utilisateurs', function ($query) use ($user2) {
        $query->where('utilisateur_id', $user2->id);
    })
        ->whereHas('utilisateurs', function ($query) use ($user1) {
            $query->where('utilisateur_id', $user1->id);
        })
        ->first();

    $conversation = null;

    if ($existing) {
        echo "✅ Conversation déjà existante trouvée (ID: {$existing->id})\n";
        echo "   Utilisateurs: " . $existing->utilisateurs()->count() . "\n";
        $conversation = $existing;
    } else {
        echo "📝 Aucune conversation existante, création en cours...\n";

        // Create conversation
        $conversation = Conversation::create();
        echo "✅ Conversation créée (ID: {$conversation->id})\n";

        // Attach users
        $conversation->utilisateurs()->attach([
            $user1->id,
            $user2->id
        ]);

        // Verify
        $count = $conversation->utilisateurs()->count();
        echo "✅ Utilisateurs attachés: {$count}\n";

        if ($count === 2) {
            echo "✅ Vérification: " . $conversation->utilisateurs->pluck('nom')->implode(', ') . "\n";
            echo "✅ TEST RÉUSSI - Conversation créée avec succès!\n\n";
        } else {
            echo "❌ ERREUR: Seuls {$count} utilisateurs attachés (attendu: 2)\n";
        }
    }

    // Test showing conversation with both users
    echo "--- Vérification: Conversation accessible par les deux utilisateurs ---\n";

    if ($conversation) {
        $hasUser1 = $conversation->utilisateurs()->where('utilisateur_id', $user1->id)->exists();
        $hasUser2 = $conversation->utilisateurs()->where('utilisateur_id', $user2->id)->exists();

        echo "User1 dans la conversation: " . ($hasUser1 ? "✅ OUI" : "❌ NON") . "\n";
        echo "User2 dans la conversation: " . ($hasUser2 ? "✅ OUI" : "❌ NON") . "\n";

        if ($hasUser1 && $hasUser2) {
            echo "\n✅ TEST COMPLET RÉUSSI!\n";
        }
    }

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
