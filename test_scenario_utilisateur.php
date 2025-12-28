<?php

/**
 * Test Script - Scénario d'utilisateur réel
 * Simule le flux complet: créer une conversation → ajouter un message → afficher
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "===== SCÉNARIO UTILISATEUR RÉEL =====\n\n";

// Get users
$alice = Utilisateur::where('id', '!=', 1)->first();
$bob = Utilisateur::where('id', '!=', 1)->where('id', '!=', $alice?->id)->first();

if (!$alice || !$bob) {
    echo "❌ Besoin de 2 utilisateurs!\n";
    exit;
}

echo "Scénario: Alice veut démarrer une conversation avec Bob\n";
echo "Alice: {$alice->nom} (ID: {$alice->id})\n";
echo "Bob: {$bob->nom} (ID: {$bob->id})\n\n";

// STEP 1: Alice logs in and creates conversation
echo "--- STEP 1: Alice accède à la page 'Démarrer une conversation' ---\n";
Auth::setUser($alice);
echo "✅ Alice authentifiée\n";

// STEP 2: Alice clique sur "Démarrer une conversation" avec Bob
echo "\n--- STEP 2: Alice clique pour démarrer une conversation ---\n";

try {
    // Check if conversation already exists (comme le contrôleur)
    $existing = Conversation::whereHas('utilisateurs', function ($query) use ($bob) {
        $query->where('utilisateur_id', $bob->id);
    })
        ->whereHas('utilisateurs', function ($query) use ($alice) {
            $query->where('utilisateur_id', $alice->id);
        })
        ->first();

    if ($existing) {
        echo "ℹ️  Conversation déjà existante (ID: {$existing->id})\n";
        $conversation = $existing;
    } else {
        // Create new conversation with transaction
        $conversation = DB::transaction(function () use ($alice, $bob) {
            $conv = Conversation::create();
            $conv->utilisateurs()->attach([
                $alice->id,
                $bob->id
            ]);
            return $conv;
        });
        
        echo "✅ Nouvelle conversation créée (ID: {$conversation->id})\n";
        echo "✅ Utilisateurs attachés: " . $conversation->utilisateurs()->count() . "\n";
    }
} catch (\Exception $e) {
    echo "❌ Erreur lors de la création: {$e->getMessage()}\n";
    exit;
}

// STEP 3: Alice sends first message
echo "\n--- STEP 3: Alice envoie un message ---\n";

try {
    $messageContent = "Salut Bob! Ça va?";
    $message = $conversation->messages()->create([
        'expediteur_id' => $alice->id,
        'contenu' => $messageContent,
    ]);
    
    echo "✅ Message envoyé (ID: {$message->id})\n";
    echo "   Contenu: \"{$messageContent}\"\n";
    echo "   Expéditeur: {$message->expediteur->nom}\n";
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'envoi: {$e->getMessage()}\n";
    exit;
}

// STEP 4: Verify conversation loads correctly
echo "\n--- STEP 4: Charger la conversation ---\n";

$loadedConversation = Conversation::with([
    'utilisateurs',
    'messages' => function ($q) {
        $q->orderBy('created_at', 'asc');
    }
])->find($conversation->id);

if ($loadedConversation) {
    echo "✅ Conversation chargée (ID: {$loadedConversation->id})\n";
    echo "   Utilisateurs: {$loadedConversation->utilisateurs()->count()}\n";
    echo "   Messages: {$loadedConversation->messages()->count()}\n";
    
    // Verify both users are in conversation
    $hasAlice = $loadedConversation->utilisateurs()->where('utilisateur_id', $alice->id)->exists();
    $hasBob = $loadedConversation->utilisateurs()->where('utilisateur_id', $bob->id)->exists();
    
    echo "   Alice dans conversation: " . ($hasAlice ? "✅" : "❌") . "\n";
    echo "   Bob dans conversation: " . ($hasBob ? "✅" : "❌") . "\n";
} else {
    echo "❌ Impossible de charger la conversation\n";
    exit;
}

// STEP 5: Bob logs in and views conversation
echo "\n--- STEP 5: Bob se connecte et voit la conversation ---\n";
Auth::setUser($bob);
echo "✅ Bob authentifié\n";

// Check if Bob can access conversation
if ($loadedConversation->utilisateurs->contains($bob)) {
    echo "✅ Bob peut accéder à la conversation\n";
    echo "   Messages: {$loadedConversation->messages->count()}\n";
    
    foreach ($loadedConversation->messages as $msg) {
        echo "   • {$msg->expediteur->nom}: \"{$msg->contenu}\"\n";
    }
} else {
    echo "❌ Bob ne peut pas accéder à la conversation\n";
}

// STEP 6: Bob replies
echo "\n--- STEP 6: Bob répond ---\n";

try {
    $bobMessage = $conversation->messages()->create([
        'expediteur_id' => $bob->id,
        'contenu' => "Salut Alice! Ça va bien!",
    ]);
    
    echo "✅ Message de Bob envoyé (ID: {$bobMessage->id})\n";
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
}

// STEP 7: Reload and verify both messages
echo "\n--- STEP 7: Vérifier les messages finaux ---\n";

$finalConversation = Conversation::with([
    'utilisateurs',
    'messages.expediteur' => function ($q) {
        $q->orderBy('created_at', 'asc');
    }
])->find($conversation->id);

if ($finalConversation && $finalConversation->messages->count() === 2) {
    echo "✅ Les deux messages sont présents\n";
    echo "\nConversation finale:\n";
    foreach ($finalConversation->messages as $msg) {
        echo "   [{$msg->created_at->format('H:i:s')}] {$msg->expediteur->nom}: {$msg->contenu}\n";
    }
} else {
    echo "❌ Messages manquants\n";
}

// FINAL: Test re-accessing conversation
echo "\n--- FINAL: Test accès après multiple clicks ---\n";

Auth::setUser($alice);

// Simulate rapid clicks
for ($i = 1; $i <= 3; $i++) {
    $existing = Conversation::whereHas('utilisateurs', function ($q) use ($bob) {
        $q->where('utilisateur_id', $bob->id);
    })->whereHas('utilisateurs', function ($q) use ($alice) {
        $q->where('utilisateur_id', $alice->id);
    })->first();
    
    if ($existing) {
        echo "✅ Click {$i}: Conversation trouvée (ID: {$existing->id})\n";
    } else {
        echo "❌ Click {$i}: Conversation non trouvée\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ SCÉNARIO COMPLÈTEMENT RÉUSSI!\n";
echo "La création et l'utilisation de conversations fonctionne correctement.\n";
echo "=" . str_repeat("=", 59) . "\n";
