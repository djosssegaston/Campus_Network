#!/bin/bash
# Commands to run after applying corrections

echo "🔄 Campus Network - Post-Correction Setup"
echo "==========================================="

# Clear caches
echo "📦 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Refresh migrations with soft deletes
echo "🗄️  Running migrations..."
php artisan migrate:refresh --seed

# Verify models
echo "✅ Verifying models..."
php artisan tinker << 'EOF'
$user = \App\Models\Utilisateur::first();
echo "User found: " . ($user ? $user->nom : "None") . "\n";
echo "User has role: " . ($user && $user->role ? "Yes" : "No") . "\n";
echo "User has publications: " . ($user && $user->publications()->count() . " publications") . "\n";
EOF

# List all routes
echo "📋 Available routes:"
php artisan route:list --path=api

echo ""
echo "✅ Setup complete!"
echo ""
echo "📚 Next: Read GUIDE_TESTING.md for comprehensive tests"
