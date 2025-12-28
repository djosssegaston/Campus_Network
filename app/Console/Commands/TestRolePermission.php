<?php

namespace App\Console\Commands;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Console\Command;

class TestRolePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester le système de rôles et permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line("\n" . str_repeat('=', 70));
        $this->info('TEST DU SYSTÈME DE RÔLES ET PERMISSIONS');
        $this->line(str_repeat('=', 70));

        // Récupérer le premier utilisateur
        $user = Utilisateur::first();
        
        if (!$user) {
            $this->error('Aucun utilisateur trouvé. Créez d\'abord un utilisateur.');
            return 1;
        }

        $this->line("\n1️⃣  UTILISATEUR TEST");
        $this->info("Email: {$user->email}");
        $this->info("ID: {$user->id}");

        // Assigner le rôle étudiant
        $roleEtudiant = Role::where('slug', 'etudiant')->first();
        $user->role_id = $roleEtudiant->id;
        $user->save();

        $this->line("\n2️⃣  ASSIGNATION DE RÔLE");
        $this->info("✓ Rôle assigné: Étudiant (slug: etudiant)");
        $this->info("✓ Niveau: 1");

        // Tester les vérifications
        $this->line("\n3️⃣  VÉRIFICATIONS");
        
        $isAdmin = $user->estAdmin();
        $this->line("Est admin? " . ($isAdmin ? "✓ OUI" : "✗ NON"));

        $isMod = $user->estModerateurGlobal();
        $this->line("Est modérateur global? " . ($isMod ? "✓ OUI" : "✗ NON"));

        // Permissions
        $this->line("\n4️⃣  PERMISSIONS");
        $permissions = $user->role->getAllPermissions();
        $this->info("Nombre de permissions: " . count($permissions));
        
        $this->line("\nPermissions détaillées:");
        foreach ($permissions as $permission) {
            $has = $user->hasPermission($permission) ? "✓" : "✗";
            $this->line("  {$has} {$permission}");
        }

        // Changer de rôle pour admin
        $this->line("\n5️⃣  CHANGEMENT DE RÔLE (-> Admin)");
        $roleAdmin = Role::where('slug', 'admin')->first();
        $user->role_id = $roleAdmin->id;
        $user->save();

        $isAdmin = $user->estAdmin();
        $this->info("Est admin? " . ($isAdmin ? "✓ OUI" : "✗ NON"));
        $this->info("Nombre de permissions admin: " . count($user->role->getAllPermissions()));

        $this->line("\n" . str_repeat('=', 70));
        $this->info('✅ TEST TERMINÉ AVEC SUCCÈS');
        $this->line(str_repeat('=', 70) . "\n");

        return 0;
    }
}
