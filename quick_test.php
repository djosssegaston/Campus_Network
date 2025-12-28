#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Utilisateur;

$users = Utilisateur::count();
echo "Total utilisateurs: $users\n";

$user1 = Utilisateur::first();
if ($user1) {
    echo "Premier utilisateur: {$user1->nom} (ID: {$user1->id})\n";
}
