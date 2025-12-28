<?php
/**
 * TEST: Création de conversations privées
 * Analyse complète du flux de création
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  🔍 ANALYSE - CRÉATION DE CONVERSATIONS PRIVÉES             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Get test users
$user1 = Utilisateur::where('id', '!=', 1)->first();
$user2 = Utilisateur::where('id', '!=', 1)->where('id', '!=', $user1?->id)->first();

if (!$user1 || !$user2) {
    echo "❌ Besoin de 2 utilisateurs pour tester\n";
    exit(1);
}

echo "👤 User 1: {$user1->nom} (ID: {$user1->id})\n";
echo "👤 User 2: {$user2->nom} (ID: {$user2->id})\n\n";

// TEST 1: Check existing conversations
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 1: Vérifier les conversations existantes\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$existingConv = Conversation::whereHas('utilisateurs', function ($q) use ($user1) {
    $q->where('utilisateur_id', $user1->id);
})
->whereHas('utilisateurs', function ($q) use ($user2) {
    $q->where('utilisateur_id', $user2->id);
})
->first();

if ($existingConv) {
    echo "✅ Conversation existante trouvée (ID: {$existingConv->id})\n";
    echo "   Utilisateurs attachés: {$existingConv->utilisateurs()->count()}\n";
    echo "   Messages: {$existingConv->messages()->count()}\n";
} else {
    echo "ℹ️  Aucune conversation existante\n";
}
echo "\n";

// TEST 2: Create new conversation
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 2: Créer une nouvelle conversation\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $newConversation = DB::transaction(function () use ($user1, $user2) {
        $conv = Conversation::create();
        echo "   Étape 1: Conversation créée (ID: {$conv->id})\n";
        
        // Attach users
        $conv->utilisateurs()->attach([
            $user1->id,
            $user2->id
        ]);
        echo "   Étape 2: Utilisateurs attachés\n";
        
        return $conv;
    });
    
    // Verify
    $count = $newConversation->utilisateurs()->count();
    echo "   Étape 3: Vérification - Utilisateurs attachés: {$count}/2\n";
    
    if ($count === 2) {
        echo "✅ Conversation créée avec succès\n";
    } else {
        echo "❌ PROBLÈME: Seulement {$count}/2 utilisateurs attachés\n";
    }
} catch (\Exception $e) {
    echo "❌ ERREUR: {$e->getMessage()}\n";
}
echo "\n";

// TEST 3: Check user relationships
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 3: Vérifier les relations utilisateur-conversation\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$conv1Conversations = $user1->conversations()->count();
$conv2Conversations = $user2->conversations()->count();

echo "   User 1 conversations: {$conv1Conversations}\n";
echo "   User 2 conversations: {$conv2Conversations}\n";

if ($conv1Conversations > 0 && $conv2Conversations > 0) {
    echo "✅ Tous deux ont des conversations\n";
} else {
    echo "⚠️  L'un des utilisateurs n'a pas de conversations\n";
}
echo "\n";

// TEST 4: Check if relationship is properly linked
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 4: Vérifier la liaison bidirectionnelle\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$common = Conversation::whereHas('utilisateurs', function ($q) use ($user1) {
    $q->where('utilisateur_id', $user1->id);
})
->whereHas('utilisateurs', function ($q) use ($user2) {
    $q->where('utilisateur_id', $user2->id);
})
->count();

echo "   Conversations communes: {$common}\n";

if ($common > 0) {
    echo "✅ Les utilisateurs partagent au moins une conversation\n";
} else {
    echo "❌ PROBLÈME: Les utilisateurs ne partagent aucune conversation\n";
}
echo "\n";

// TEST 5: Check models
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 5: Vérifier la configuration des modèles\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$conv = Conversation::first();
if ($conv) {
    echo "   Conversation ID: {$conv->id}\n";
    
    // Check relationship
    $hasUtilisateurs = method_exists($conv, 'utilisateurs');
    echo "   Méthode 'utilisateurs': " . ($hasUtilisateurs ? "✅ Existe" : "❌ Manquante") . "\n";
    
    $hasMessages = method_exists($conv, 'messages');
    echo "   Méthode 'messages': " . ($hasMessages ? "✅ Existe" : "❌ Manquante") . "\n";
    
    // Try to load relationships
    try {
        $conv->load('utilisateurs', 'messages');
        echo "   Chargement relations: ✅ OK\n";
    } catch (\Exception $e) {
        echo "   Chargement relations: ❌ ERREUR\n";
    }
} else {
    echo "   ℹ️  Aucune conversation trouvée pour tester\n";
}
echo "\n";

// TEST 6: Database integrity
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 6: Intégrité de la base de données\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$totalConversations = Conversation::count();
$totalAttachments = DB::table('conversation_utilisateurs')->count();
$totalMessages = Message::count();

echo "   Conversations: {$totalConversations}\n";
echo "   Attachements utilisateurs: {$totalAttachments}\n";
echo "   Messages: {$totalMessages}\n";

if ($totalConversations > 0 && $totalAttachments >= $totalConversations * 2) {
    echo "✅ Base de données cohérente\n";
} else {
    echo "⚠️  Possibles incohérences\n";
}
echo "\n";

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ ANALYSE COMPLÉTÉE                                       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";
