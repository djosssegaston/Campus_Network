#!/bin/bash

cd c:\\Users\\HP\\Campus_Network

echo "===== Exécution des migrations ====="
php artisan migrate --force

echo "===== Création des permissions et rôles ====="
php artisan db:seed --class=PermissionSeeder

echo "===== Nettoyage des caches ====="
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

echo "===== Complet! ====="
