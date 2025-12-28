<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use App\Models\Role;

class TestLinksAdmin extends Command
{
    protected $signature = 'test:admin-links';
    protected $description = 'Test les liens admin et la vérification des rôles';

    public function handle()
    {
        $this->info('');
        $this->info(str_repeat("=", 60));
        $this->info('TEST: VÉRIFICATION DES LIENS ADMIN');
        $this->info(str_repeat("=", 60));

        // 1. Rôles
        $this->line('');
        $this->info('1️⃣  RÔLES EN BASE DE DONNÉES:');
        $roles = Role::all();
        if ($roles->isEmpty()) {
            $this->error('❌ Aucun rôle en base de données!');
            $this->warn('   → Exécuter: php artisan db:seed --class=RolePermissionSeeder');
        } else {
            foreach ($roles as $role) {
                $count = $role->utilisateurs()->count();
                $isAdmin = $role->isAdmin();
                $icon = $isAdmin ? '👑' : '👤';
                $this->line("   $icon {$role->nom} (slug: {$role->slug}) - Users: $count");
            }
        }

        // 2. Utilisateurs
        $this->line('');
        $this->info('2️⃣  UTILISATEURS:');
        $users = Utilisateur::with('role')->limit(5)->get();
        if ($users->isEmpty()) {
            $this->error('❌ Aucun utilisateur!');
        } else {
            foreach ($users as $user) {
                $role = $user->role ? $user->role->nom : 'AUCUN RÔLE';
                $this->line("   • {$user->email} → $role");
            }
        }

        // 3. Test estAdmin()
        $this->line('');
        $this->info('3️⃣  TEST DE LA MÉTHODE estAdmin():');
        foreach ($users as $user) {
            $isAdmin = $user->estAdmin();
            $icon = $isAdmin ? '✅' : '❌';
            $role = $user->role ? $user->role->nom : 'AUCUN';
            $this->line("   $icon {$user->email} → estAdmin(): " . ($isAdmin ? 'OUI' : 'NON') . " (Rôle: $role)");
        }

        // 4. Vérifier qu'un admin existe
        $this->line('');
        $this->info('4️⃣  UTILISATEURS ADMIN:');
        $adminCount = Utilisateur::whereHas('role', function ($query) {
            $query->whereIn('slug', ['admin', 'administrateur', 'super_admin']);
        })->count();

        if ($adminCount == 0) {
            $this->error('❌ Aucun utilisateur admin trouvé!');
            $this->warn('   Actions:');
            $this->warn('   1. Créer un rôle admin: php artisan db:seed --class=RolePermissionSeeder');
            $this->warn('   2. Assigner le rôle: php artisan tinker');
            $this->warn('      > $user = User::first(); $user->role_id = Role::where("slug", "admin")->first()->id; $user->save();');
        } else {
            $this->line("   ✅ $adminCount utilisateur(s) admin trouvé(s)");
            // Lister les admins
            $admins = Utilisateur::whereHas('role', function ($query) {
                $query->whereIn('slug', ['admin', 'administrateur', 'super_admin']);
            })->with('role')->get();
            
            foreach ($admins as $admin) {
                $this->line("      • {$admin->email} ({$admin->role->nom})");
            }
        }

        // 5. Routes
        $this->line('');
        $this->info('5️⃣  ROUTES ADMIN:');
        $routes = ['admin.dashboard', 'users.index', 'roles.index'];
        foreach ($routes as $route) {
            try {
                $url = route($route);
                $this->line("   ✅ $route → $url");
            } catch (\Exception $e) {
                $this->error("   ❌ $route → ROUTE NON TROUVÉE");
            }
        }

        $this->line('');
        $this->info(str_repeat("=", 60));
        $this->info('✅ TEST TERMINÉ');
        $this->info(str_repeat("=", 60));
        $this->line('');
    }
}
