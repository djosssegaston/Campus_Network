<?php

require 'vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = new Application(base_path());
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create test user
$this->artisan('tinker', [
    'command' => 'php',
    'input' => '
        $user = new \App\Models\Utilisateur();
        $user->nom = "Test User";
        $user->email = "test@example.com";
        $user->mot_de_passe = "Test1234!";
        $user->role_id = 2;
        $user->save();
        echo "User created: " . $user->email;
        exit();
    '
]);
?>
