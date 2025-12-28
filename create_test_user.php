<?php

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Créer un utilisateur test
$user = Utilisateur::updateOrCreate(
    ['email' => 'test@campus.local'],
    [
        'nom' => 'Test User',
        'mot_de_passe' => 'Test1234!',
        'role_id' => 2,
        'filiere' => 'Informatique',
        'annee_etude' => 1,
    ]
);

echo "✓ User created/updated: " . $user->email . "\n";
echo "✓ ID: " . $user->id . "\n";

// Test token creation
$token = $user->createToken('test-token')->plainTextToken;
echo "✓ Token created\n";

// Save to file for testing
file_put_contents(__DIR__ . '/test_token.txt', $token);
echo "✓ Token saved to test_token.txt\n";

exit(0);
?>
