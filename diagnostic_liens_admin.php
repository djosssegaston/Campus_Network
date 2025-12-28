<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\Utilisateur;
use App\Models\Role;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

echo "\n=== DIAGNOSTIC LIENS ADMIN ===\n";
echo str_repeat("=", 50) . "\n";

// 1. Vérifier les utilisateurs
echo "\n1️⃣ UTILISATEURS ET LEURS RÔLES:\n";
echo str_repeat("-", 50) . "\n";
$users = Utilisateur::with('role')->limit(10)->get();
if ($users->isEmpty()) {
    echo "❌ Aucun utilisateur en base de données!\n";
} else {
    foreach ($users as $user) {
        $role = $user->role ? $user->role->nom : 'AUCUN RÔLE!';
        $estAdmin = $user->estAdmin();
        $icon = $estAdmin ? '✅' : '❌';
        echo "$icon {$user->email} | Rôle: {$role} | Admin: " . ($estAdmin ? 'OUI' : 'NON') . "\n";
    }
}

// 2. Vérifier les rôles en BD
echo "\n2️⃣ RÔLES EN BASE DE DONNÉES:\n";
echo str_repeat("-", 50) . "\n";
$roles = Role::all();
if ($roles->isEmpty()) {
    echo "❌ Aucun rôle en base de données!\n";
} else {
    foreach ($roles as $role) {
        $count = $role->utilisateurs()->count();
        $isAdmin = $role->isAdmin();
        $icon = $isAdmin ? '👑' : '👤';
        echo "$icon {$role->nom} (slug: {$role->slug}) | Users: {$count} | isAdmin: " . ($isAdmin ? 'OUI' : 'NON') . "\n";
    }
}

// 3. Vérifier utilisateur admin@test.com
echo "\n3️⃣ UTILISATEUR ADMIN SPÉCIFIQUE:\n";
echo str_repeat("-", 50) . "\n";
$admin = Utilisateur::where('email', 'admin@test.com')->first();
if ($admin) {
    echo "✅ Email: {$admin->email}\n";
    echo "   Role ID: {$admin->role_id}\n";
    echo "   Role: " . ($admin->role ? $admin->role->nom : 'AUCUN') . "\n";
    echo "   estAdmin(): " . ($admin->estAdmin() ? 'OUI ✅' : 'NON ❌') . "\n";
    if ($admin->role) {
        echo "   isAdmin(): " . ($admin->role->isAdmin() ? 'OUI ✅' : 'NON ❌') . "\n";
    }
} else {
    echo "❌ Aucun utilisateur admin@test.com\n";
}

// 4. Tester les routes
echo "\n4️⃣ VÉRIFIER LES ROUTES ADMIN:\n";
echo str_repeat("-", 50) . "\n";
try {
    $routeAdmin = route('admin.dashboard');
    echo "✅ Route admin.dashboard existe: {$routeAdmin}\n";
} catch (Exception $e) {
    echo "❌ Route admin.dashboard n'existe pas\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ Diagnostic terminé\n";
?>
