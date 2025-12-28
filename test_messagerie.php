<?php

// Script de test pour la messagerie

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Utilisateur;

echo "=== TEST MESSAGERIE ===\n\n";

// Récupérer 2 utilisateurs
$user1 = Utilisateur::first();
$user2 = Utilisateur::skip(1)->first();

if (!$user1 || !$user2) {
    echo "❌ Besoin de 2 utilisateurs!\n";
    exit;
}

echo "✅ Utilisateur 1: " . $user1->nom . " (ID: " . $user1->id . ")\n";
echo "✅ Utilisateur 2: " . $user2->nom . " (ID: " . $user2->id . ")\n";

// Créer une conversation
$conversation = Conversation::create();
$conversation->utilisateurs()->attach([$user1->id, $user2->id]);
echo "\n✅ Conversation créée (ID: " . $conversation->id . ")\n";
echo "✅ Utilisateurs attachés: " . $conversation->utilisateurs->count() . "\n";

// Créer des messages
$msg1 = $conversation->messages()->create([
    'expediteur_id' => $user1->id,
    'contenu' => 'Salut ' . $user2->nom . '! Comment ça va?',
]);
echo "\n✅ Message 1 créé: \"" . $msg1->contenu . "\"\n";

$msg2 = $conversation->messages()->create([
    'expediteur_id' => $user2->id,
    'contenu' => 'Bien! Et toi?',
]);
echo "✅ Message 2 créé: \"" . $msg2->contenu . "\"\n";

// Vérifier les messages
$messages = $conversation->messages()->with('expediteur')->get();
echo "\n✅ Messages dans la conversation: " . $messages->count() . "\n";
foreach ($messages as $msg) {
    echo "  - " . $msg->expediteur->nom . ": " . $msg->contenu . "\n";
}

// Marquer un message comme lu
$msg1->update(['read_at' => now()]);
echo "\n✅ Message marqué comme lu\n";

// Vérifier les conversations d'un utilisateur
$convs = Conversation::whereHas('utilisateurs', function ($q) use ($user1) {
    $q->where('utilisateur_id', $user1->id);
})->get();
echo "\n✅ Conversations de " . $user1->nom . ": " . $convs->count() . "\n";

echo "\n✅ TEST RÉUSSI!\n";
