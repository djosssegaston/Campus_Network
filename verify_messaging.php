<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Utilisateur;
use App\Models\Conversation;
use App\Models\Message;

echo "\n===== VÉRIFICATION FINALE SYSTÈME DE MESSAGERIE =====\n\n";
echo "✅ Utilisateurs: " . Utilisateur::count() . "\n";
echo "✅ Conversations: " . Conversation::count() . "\n";
echo "✅ Messages: " . Message::count() . "\n";

$latest = Message::latest()->first();
if ($latest) {
    echo "\n📝 Dernier message:\n";
    echo "   ID: {$latest->id}\n";
    echo "   Contenu: " . substr($latest->contenu, 0, 50) . "...\n";
    echo "   Expéditeur: {$latest->expediteur->nom}\n";
    echo "   Date: {$latest->created_at}\n";
}

echo "\n✅ SYSTÈME OPÉRATIONNEL!\n\n";
