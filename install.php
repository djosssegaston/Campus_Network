<?php
/**
 * Script d'installation des nouvelles fonctionnalités
 * Exécute les migrations et seeders
 */

// Charger Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Exécuter les migrations
echo "\n===== Exécution des migrations =====\n";
$kernel->call('migrate', ['--force' => true]);

// Exécuter le seeder de permissions
echo "\n===== Création des permissions et rôles =====\n";
$kernel->call('db:seed', ['--class' => 'PermissionSeeder']);

// Nettoyer les caches
echo "\n===== Nettoyage des caches =====\n";
$kernel->call('cache:clear');
$kernel->call('config:clear');
$kernel->call('route:clear');
$kernel->call('view:clear');
$kernel->call('optimize:clear');

echo "\n===== Installation complète! =====\n";
echo "Vous pouvez maintenant accéder au panel d'administration.\n";
echo "URL: http://localhost:8000/admin\n\n";
