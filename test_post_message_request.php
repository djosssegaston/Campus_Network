<?php

/**
 * Test Script - Simulation d'une requête POST d'envoi de message
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\MessageController;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;

echo "===== TEST REQUÊTE POST - ENVOI DE MESSAGE =====\n\n";

// Get users
$sender = Utilisateur::where('id', '!=', 1)->first();
$recipient = Utilisateur::where('id', '!=', 1)->where('id', '!=', $sender?->id)->first();

if (!$sender || !$recipient) {
    echo "❌ Besoin de 2 utilisateurs!\n";
    exit;
}

echo "Sender: {$sender->nom} (ID: {$sender->id})\n";
echo "Recipient: {$recipient->nom} (ID: {$recipient->id})\n\n";

// Authenticate as sender
Auth::setUser($sender);
echo "✅ Sender authentifié\n\n";

// Create fake POST request
echo "--- Simulation: Requête POST vers /messages ---\n";

$request = new Request([
    'recipient_id' => (string)$recipient->id,
    'contenu' => 'Hello from test!',
]);

// Manually set auth on request
$request->setUserResolver(function () use ($sender) {
    return $sender;
});

// Get StoreMessageRequest validator
$storeRequest = new StoreMessageRequest();
$storeRequest->initialize(
    [],
    ['recipient_id' => (string)$recipient->id, 'contenu' => 'Hello from test!'],
    [],
    [],
    [],
    $_SERVER,
    null
);
$storeRequest->setUserResolver(function () use ($sender) {
    return $sender;
});

echo "POST Data:\n";
echo "  recipient_id: {$recipient->id}\n";
echo "  contenu: Hello from test!\n\n";

// Test authorization
echo "--- Test 1: Authorization ---\n";
if ($storeRequest->authorize()) {
    echo "✅ Utilisateur autorisé\n";
} else {
    echo "❌ Utilisateur non autorisé\n";
}

// Test validation rules
echo "\n--- Test 2: Validation Rules ---\n";
$rules = $storeRequest->rules();
echo "Règles de validation appliquées:\n";
foreach ($rules as $field => $fieldRules) {
    if (is_array($fieldRules)) {
        $ruleStrings = [];
        foreach ($fieldRules as $rule) {
            if (is_callable($rule)) {
                $ruleStrings[] = 'custom_closure';
            } else {
                $ruleStrings[] = $rule;
            }
        }
        echo "  {$field}: " . implode('|', $ruleStrings) . "\n";
    } else {
        echo "  {$field}: {$fieldRules}\n";
    }
}

// Test validator
echo "\n--- Test 3: Validate Input ---\n";
$validator = \Illuminate\Support\Facades\Validator::make(
    ['recipient_id' => (string)$recipient->id, 'contenu' => 'Hello from test!'],
    $rules
);

if ($validator->passes()) {
    echo "✅ Validation passed\n";
    echo "   Données validées:\n";
    foreach ($validator->validated() as $key => $value) {
        echo "   - {$key}: {$value}\n";
    }
} else {
    echo "❌ Validation failed:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "   - {$error}\n";
    }
}

// Test edge cases
echo "\n--- Test 4: Edge Cases ---\n";

// Case 1: Empty message
$validator1 = \Illuminate\Support\Facades\Validator::make(
    ['recipient_id' => (string)$recipient->id, 'contenu' => '   '],
    $rules
);
echo "Case 1 - Message vide (spaces): " . ($validator1->fails() ? "❌ FAILED (correct)" : "✅ PASSED") . "\n";

// Case 2: Message too long
$longMsg = str_repeat('a', 5001);
$validator2 = \Illuminate\Support\Facades\Validator::make(
    ['recipient_id' => (string)$recipient->id, 'contenu' => $longMsg],
    $rules
);
echo "Case 2 - Message trop long (5001 chars): " . ($validator2->fails() ? "❌ FAILED (correct)" : "✅ PASSED") . "\n";

// Case 3: Self message
$validator3 = \Illuminate\Support\Facades\Validator::make(
    ['recipient_id' => (string)$sender->id, 'contenu' => 'Test'],
    $rules
);
echo "Case 3 - Message à soi-même: " . ($validator3->fails() ? "❌ FAILED (correct)" : "✅ PASSED") . "\n";

// Case 4: Non-existent recipient
$validator4 = \Illuminate\Support\Facades\Validator::make(
    ['recipient_id' => '99999', 'contenu' => 'Test'],
    $rules
);
echo "Case 4 - Destinataire inexistant: " . ($validator4->fails() ? "❌ FAILED (correct)" : "✅ PASSED") . "\n";

// Case 5: Valid message
$validator5 = \Illuminate\Support\Facades\Validator::make(
    ['recipient_id' => (string)$recipient->id, 'contenu' => 'This is a valid message!'],
    $rules
);
echo "Case 5 - Message valide: " . ($validator5->passes() ? "✅ PASSED" : "❌ FAILED") . "\n";

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ TEST DE REQUÊTE POST COMPLÉTÉ!\n";
echo "=" . str_repeat("=", 60) . "\n";
