#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Groupe;
use App\Models\GroupeMessage;

echo "=== TEST MESSAGES GROUPE ===\n\n";

$groupe = Groupe::first();
if ($groupe) {
    echo "✅ Groupe trouvé: {$groupe->nom}\n";
    echo "   ID: {$groupe->id}\n";
    
    $messages = $groupe->messages()->count();
    echo "   Messages: $messages\n";
    
    // Vérifier les paramètres
    $settings = $groupe->getSettings();
    echo "   Autoriser messages: " . ($settings->autoriser_messages ? '✅' : '❌') . "\n";
    
    // Afficher les 3 derniers messages
    if ($messages > 0) {
        echo "\n📨 Derniers messages:\n";
        $lastMessages = $groupe->messages()->latest()->take(3)->with('utilisateur')->get();
        foreach ($lastMessages as $msg) {
            echo "   - {$msg->utilisateur->nom}: " . substr($msg->contenu ?? '(fichier)', 0, 50) . "\n";
        }
    }
} else {
    echo "❌ Aucun groupe trouvé\n";
}

echo "\n=== FIN ===\n";
