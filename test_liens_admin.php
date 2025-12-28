<?php
// Test script pour vérifier les liens admin

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Initialiser l'application
$app->make(\Illuminate\Contracts\Http\Kernel::class);

// Charger les migrations
\Illuminate\Support\Facades\DB::connection();

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

echo "\n" . str_repeat("=", 60) . "\n";
echo "TEST: VÉRIFICATION DES LIENS ADMIN\n";
echo str_repeat("=", 60) . "\n";

// 1. Vérifier les rôles
echo "\n1️⃣  RÔLES EN BASE DE DONNÉES:\n";
$roles = DB::table('roles')->get();
if ($roles->isEmpty()) {
    echo "❌ ERREUR: Aucun rôle en base de données!\n";
    echo "   → Exécuter: php artisan db:seed --class=RolePermissionSeeder\n";
} else {
    foreach ($roles as $role) {
        echo "   ✓ {$role->nom} (slug: {$role->slug})\n";
    }
}

// 2. Vérifier les utilisateurs
echo "\n2️⃣  UTILISATEURS:\n";
$users = DB::table('utilisateurs')
    ->leftJoin('roles', 'utilisateurs.role_id', '=', 'roles.id')
    ->select('utilisateurs.email', 'utilisateurs.role_id', 'roles.nom as role_name', 'roles.slug')
    ->limit(5)
    ->get();

if ($users->isEmpty()) {
    echo "❌ ERREUR: Aucun utilisateur!\n";
} else {
    foreach ($users as $user) {
        $roleInfo = $user->role_name ? "{$user->role_name} ({$user->slug})" : "AUCUN RÔLE";
        echo "   • {$user->email} → $roleInfo\n";
    }
}

// 3. Tester estAdmin() pour chaque utilisateur
echo "\n3️⃣  TEST DE LA MÉTHODE estAdmin():\n";
$users = Utilisateur::with('role')->limit(5)->get();
foreach ($users as $user) {
    $isAdmin = $user->estAdmin();
    $icon = $isAdmin ? '✅' : '❌';
    $roleInfo = $user->role ? $user->role->nom : 'AUCUN RÔLE';
    echo "   $icon {$user->email} → estAdmin(): " . ($isAdmin ? 'OUI' : 'NON') . " (Rôle: $roleInfo)\n";
}

// 4. Vérifier que au moins un admin existe
echo "\n4️⃣  VÉRIFIER QU'UN ADMIN EXISTE:\n";
$adminRoles = DB::table('roles')
    ->whereIn('slug', ['admin', 'administrateur', 'super_admin'])
    ->pluck('id')
    ->toArray();

if (empty($adminRoles)) {
    echo "❌ ERREUR: Aucun rôle admin trouvé!\n";
    echo "   Rôles admin attendus: admin, administrateur, super_admin\n";
} else {
    $adminUsers = DB::table('utilisateurs')
        ->whereIn('role_id', $adminRoles)
        ->count();
    
    if ($adminUsers == 0) {
        echo "❌ ERREUR: Aucun utilisateur avec un rôle admin!\n";
        echo "   → Créer un utilisateur admin\n";
    } else {
        echo "✅ OK: $adminUsers utilisateur(s) admin trouvé(s)\n";
    }
}

// 5. Test de route
echo "\n5️⃣  VÉRIFIER LES ROUTES ADMIN:\n";
$routes = [
    'admin.dashboard',
    'users.index',
    'roles.index',
    'admin.publications.index',
];

foreach ($routes as $route) {
    try {
        $url = route($route);
        echo "   ✅ $route → $url\n";
    } catch (Exception $e) {
        echo "   ❌ $route → ROUTE NON TROUVÉE\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "TEST TERMINÉ\n";
echo str_repeat("=", 60) . "\n";
?>
