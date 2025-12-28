<?php

namespace App\Console\Commands;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetupSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:setup {--email=admin@campus.com} {--password=admin123456} {--name=Admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer ou mettre à jour un compte super admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        $this->line("\n" . str_repeat('=', 70));
        $this->info('CONFIGURATION DU SUPER ADMIN');
        $this->line(str_repeat('=', 70));

        // Vérifier si l'utilisateur existe
        $user = Utilisateur::where('email', $email)->first();

        if ($user) {
            $this->info("\n✓ Utilisateur trouvé: {$user->email}");
        } else {
            $this->info("\n✓ Création d'un nouvel utilisateur...");
            $user = Utilisateur::create([
                'nom' => $name,
                'email' => $email,
                'mot_de_passe' => $password,
                'email_verified_at' => now(),
            ]);
            $this->info("✓ Utilisateur créé: {$user->email}");
        }

        // Assigner le rôle super_admin
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        
        if (!$superAdminRole) {
            $this->error("✗ Rôle 'super_admin' non trouvé. Exécutez: php artisan db:seed --class=RolePermissionSeeder");
            return 1;
        }

        $user->role_id = $superAdminRole->id;
        $user->save();

        $this->line("\n" . str_repeat('-', 70));
        $this->info('✅ SUPER ADMIN CONFIGURÉ AVEC SUCCÈS!');
        $this->line(str_repeat('-', 70));

        $this->info("\n📋 IDENTIFIANTS DE CONNEXION:");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("Email:       {$email}");
        $this->line("Mot de passe: {$password}");
        $this->line("Rôle:        Super Admin (niveau 10)");
        $this->line("Permissions: TOUTES (17/17)");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $this->info("\n🌐 URL DE CONNEXION:");
        $this->line("http://localhost:8000/login");

        $this->info("\n✨ PROCHAINES ÉTAPES:");
        $this->line("1. Ouvrez http://localhost:8000/login");
        $this->line("2. Entrez votre email et mot de passe");
        $this->line("3. Vous aurez accès à toutes les fonctionnalités admin");

        $this->line("\n" . str_repeat('=', 70) . "\n");

        return 0;
    }
}
