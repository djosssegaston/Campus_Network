<?php

namespace App\Console\Commands;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:assign {user_id} {role_slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigner un rôle à un utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $roleSlug = $this->argument('role_slug');

        $user = Utilisateur::find($userId);
        if (!$user) {
            $this->error("Utilisateur avec l'ID {$userId} non trouvé");
            return 1;
        }

        $role = Role::where('slug', $roleSlug)->first();
        if (!$role) {
            $this->error("Rôle '{$roleSlug}' non trouvé. Rôles disponibles: " . Role::pluck('slug')->implode(', '));
            return 1;
        }

        $user->update(['role_id' => $role->id]);
        $this->info("Rôle '{$role->nom}' assigné à {$user->email}");

        return 0;
    }
}
