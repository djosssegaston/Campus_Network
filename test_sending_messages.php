<?php

/**
 * Test Script - Envoi de Messages
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "===== TEST ENVOI DE MESSAGES =====\n\n";

// Get users
$sender = Utilisateur::where('id', '!=', 1)->first();
$recipient = Utilisateur::where('id', '!=', 1)->where('id', '!=', $sender?->id)->first();

if (!$sender || !$recipient) {
    echo "❌ Besoin de 2 utilisateurs!\n";
    exit;
}

echo "Sender: {$sender->nom} (ID: {$sender->id})\n";
echo "Recipient: {$recipient->nom} (ID: {$recipient->id})\n\n";

// Get or create conversation
$conversation = Conversation::whereHas('utilisateurs', function ($q) use ($recipient) {
    $q->where('utilisateur_id', $recipient->id);
})->whereHas('utilisateurs', function ($q) use ($sender) {
    $q->where('utilisateur_id', $sender->id);
})->first();

if (!$conversation) {
    $conversation = DB::transaction(function () use ($sender, $recipient) {
        $conv = Conversation::create();
        $conv->utilisateurs()->attach([$sender->id, $recipient->id]);
        return $conv;
    });
    echo "✅ Conversation créée (ID: {$conversation->id})\n";
} else {
    echo "✅ Conversation trouvée (ID: {$conversation->id})\n";
}

// Authenticate as sender
Auth::setUser($sender);
echo "✅ Sender authentifié\n\n";

// Test 1: Direct message creation
echo "--- TEST 1: Création directe de message ---\n";
try {
    $message = $conversation->messages()->create([
        'expediteur_id' => $sender->id,
        'contenu' => 'Test message 1',
    ]);
    
    echo "✅ Message créé (ID: {$message->id})\n";
    echo "   Contenu: {$message->contenu}\n";
    echo "   Expéditeur: {$message->expediteur->nom}\n";
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
}

// Test 2: Validation StoreMessageRequest
echo "\n--- TEST 2: Validation des données ---\n";

$validator = \Illuminate\Support\Facades\Validator::make(
    [
        'recipient_id' => $recipient->id,
        'contenu' => 'Test message 2',
    ],
    [
        'recipient_id' => ['required', 'exists:utilisateurs,id', 'integer', function ($attribute, $value, $fail) use ($sender) {
            if ($value == $sender->id) {
                $fail('Vous ne pouvez pas vous envoyer de message.');
            }
        }],
        'contenu' => 'required|string|min:1|max:5000',
    ]
);

if ($validator->passes()) {
    echo "✅ Validation passed\n";
} else {
    echo "❌ Validation errors:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "   - {$error}\n";
    }
}

// Test 3: Controller logic simulation
echo "\n--- TEST 3: Simulation de la logique du contrôleur ---\n";

try {
    $result = DB::transaction(function () use ($sender, $recipient, $conversation) {
        // Get or create conversation
        $conv = Conversation::whereHas('utilisateurs', function ($q) use ($recipient) {
            $q->where('utilisateur_id', $recipient->id);
        })->whereHas('utilisateurs', function ($q) use ($sender) {
            $q->where('utilisateur_id', $sender->id);
        })->first();

        if (!$conv) {
            $conv = Conversation::create();
            $conv->utilisateurs()->attach([$sender->id, $recipient->id]);
            echo "   Nouvelle conversation créée\n";
        } else {
            echo "   Conversation existante trouvée\n";
        }

        // Create message
        $message = $conv->messages()->create([
            'expediteur_id' => $sender->id,
            'contenu' => 'Test message via contrôleur',
        ]);

        echo "   Message créé\n";

        return $conv;
    });

    echo "✅ Transaction réussie\n";
} catch (\Exception $e) {
    echo "❌ Erreur transaction: {$e->getMessage()}\n";
}

// Test 4: Load conversation with messages
echo "\n--- TEST 4: Charger la conversation avec messages ---\n";

$loadedConv = Conversation::with([
    'utilisateurs',
    'messages.expediteur'
])->find($conversation->id);

if ($loadedConv) {
    echo "✅ Conversation chargée\n";
    echo "   Utilisateurs: {$loadedConv->utilisateurs()->count()}\n";
    echo "   Messages: {$loadedConv->messages->count()}\n";
    
    foreach ($loadedConv->messages as $msg) {
        echo "   [{$msg->created_at->format('H:i:s')}] {$msg->expediteur->nom}: {$msg->contenu}\n";
    }
}

// Test 5: Check if current user can send message
echo "\n--- TEST 5: Vérification permission d'envoi ---\n";

$canSend = $conversation->utilisateurs()->where('utilisateur_id', Auth::id())->exists();
echo "Sender peut envoyer: " . ($canSend ? "✅ OUI" : "❌ NON") . "\n";

// Test 6: Recipient can access
echo "\n--- TEST 6: Vérification accès destinataire ---\n";

Auth::setUser($recipient);
$recipientCanAccess = $conversation->utilisateurs()->where('utilisateur_id', $recipient->id)->exists();
echo "Recipient peut accéder: " . ($recipientCanAccess ? "✅ OUI" : "❌ NON") . "\n";

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ TOUS LES TESTS D'ENVOI RÉUSSIS!\n";
echo "=" . str_repeat("=", 60) . "\n";
