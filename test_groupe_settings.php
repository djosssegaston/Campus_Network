#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Groupe;
use App\Models\Utilisateur;

$groupe = Groupe::first();
if ($groupe) {
    echo 'Groupe: ' . $groupe->nom . PHP_EOL;
    $settings = $groupe->getSettings();
    echo 'Autoriser publications: ' . ($settings->autoriser_publications ? 'OUI' : 'NON') . PHP_EOL;
    echo 'Autoriser messages: ' . ($settings->autoriser_messages ? 'OUI' : 'NON') . PHP_EOL;
    
    $user = Utilisateur::first();
    if ($user) {
        echo 'Utilisateur test: ' . $user->nom . PHP_EOL;
        $isMember = $groupe->utilisateurs->contains($user);
        echo 'Est membre: ' . ($isMember ? 'OUI' : 'NON') . PHP_EOL;
    }
} else {
    echo 'Pas de groupe' . PHP_EOL;
}
