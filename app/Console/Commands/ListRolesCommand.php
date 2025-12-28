<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class ListRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lister tous les rôles et permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            $this->info('Aucun rôle trouvé. Exécutez: php artisan db:seed --class=RolePermissionSeeder');
            return;
        }

        foreach ($roles as $role) {
            $permissions = $role->permissions()->get();
            
            $this->line("\n" . str_repeat('=', 60));
            $this->info("Rôle: {$role->nom} (slug: {$role->slug})");
            $this->info("Niveau: {$role->niveau}");
            $this->info("Admin: " . ($role->isAdmin() ? 'Oui' : 'Non'));
            $this->info("Modérateur: " . ($role->isModerator() ? 'Oui' : 'Non'));
            
            if ($permissions->count() > 0) {
                $this->line("\nPermissions:");
                foreach ($permissions as $permission) {
                    $this->line("  - {$permission->nom}: {$permission->description}");
                }
            } else {
                $this->line("\nAucune permission assignée");
            }
        }

        $this->line("\n" . str_repeat('=', 60));
    }
}
