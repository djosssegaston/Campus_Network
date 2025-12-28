#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Utilisateur;
use App\Models\Conversation;

echo "=== TEST MESSAGES PRIVÉS ===\n\n";

// Trouver deux utilisateurs
$user1 = Utilisateur::where('email', 'admin@campus.test')->first();
$user2 = Utilisateur::where('email', '!=', 'admin@campus.test')->whereNotNull('email')->first();

if (!$user1 || !$user2) {
    echo "❌ Pas assez d'utilisateurs trouvés\n";
    echo "   User1: " . ($user1 ? $user1->nom : "non trouvé") . "\n";
    echo "   User2: " . ($user2 ? $user2->nom : "non trouvé") . "\n";
    exit;
}

echo "✅ Utilisateurs trouvés:\n";
echo "   User1: {$user1->nom} (ID: {$user1->id})\n";
echo "   User2: {$user2->nom} (ID: {$user2->id})\n\n";

// Vérifier les conversations
$conversations = Conversation::whereHas('utilisateurs', function($q) use ($user1) {
    $q->where('utilisateur_id', $user1->id);
})->with('utilisateurs', 'messages')->get();

echo "📨 Conversations de {$user1->nom}: {$conversations->count()}\n";

foreach ($conversations as $conv) {
    echo "   - Conversation ID: {$conv->id}\n";
    echo "     Membres: ";
    foreach ($conv->utilisateurs as $u) {
        echo "{$u->nom}, ";
    }
    echo "\n";
    echo "     Messages: {$conv->messages->count()}\n";
}

echo "\n✅ Configuration des messages privés OK\n";
