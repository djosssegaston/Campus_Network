#!/bin/bash

# ================================================
# Campus Network - Configuration & Installation
# ================================================

echo "╔════════════════════════════════════════════════════╗"
echo "║   CAMPUS NETWORK - Configuration Complète         ║"
echo "╚════════════════════════════════════════════════════╝"
echo ""

# Étape 1: Installation des dépendances
echo "📦 Étape 1: Installation des dépendances..."
composer install
npm install

# Étape 2: Configuration de l'environnement
echo "⚙️  Étape 2: Configuration environnement..."
cp .env.example .env
php artisan key:generate

# Étape 3: Préparation base de données
echo "💾 Étape 3: Préparation base de données..."
php artisan migrate:fresh --seed --seeder=RolePermissionSeeder

# Étape 4: Compilation des assets
echo "🎨 Étape 4: Compilation des assets..."
npm run build

# Étape 5: Création super admin
echo "🔐 Étape 5: Création du compte super admin..."
php artisan tinker << 'EOF'
use App\Models\Utilisateur;
use App\Models\Role;

$superAdminRole = Role::where('slug', 'super_admin')->first();

$admin = Utilisateur::updateOrCreate(
    ['email' => 'admin@campus.com'],
    [
        'nom' => 'Administrateur Campus',
        'mot_de_passe' => 'Admin123!',
        'email_verified_at' => now(),
        'role_id' => $superAdminRole->id
    ]
);

echo "✅ Super Admin créé avec succès!\n";
echo "Email: admin@campus.com\n";
echo "Mot de passe: Admin123!\n";
EOF

echo ""
echo "╔════════════════════════════════════════════════════╗"
echo "║   ✅ INSTALLATION TERMINÉE AVEC SUCCÈS             ║"
echo "╚════════════════════════════════════════════════════╝"
echo ""
echo "🚀 Pour démarrer l'application:"
echo "   php artisan serve"
echo ""
echo "🌐 Accédez à: http://localhost:8000"
echo ""
echo "👤 Identifiants super admin:"
echo "   Email: admin@campus.com"
echo "   Mot de passe: Admin123!"
echo ""
