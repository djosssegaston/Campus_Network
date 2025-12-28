<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the database with an admin user.
     */
    public function run(): void
    {
        // Créer les rôles d'abord
        $adminRole = Role::firstOrCreate(
            ['slug' => 'administrateur'],
            [
                'nom' => 'Administrateur',
                'niveau' => 100,
                'permissions' => ['manage_users', 'manage_publications', 'manage_groups']
            ]
        );

        $userRole = Role::firstOrCreate(
            ['slug' => 'etudiant'],
            [
                'nom' => 'Étudiant',
                'niveau' => 1,
                'permissions' => ['create_publication', 'comment', 'react']
            ]
        );

        // Créer l'utilisateur admin
        Utilisateur::firstOrCreate(
            ['email' => 'admin@campus.test'],
            [
                'nom' => 'Admin Campus',
                'mot_de_passe' => \Illuminate\Support\Facades\Hash::make('password'),
                'email_verified_at' => now(),
                'filiere' => 'Informatique',
                'annee_etude' => 2024,
                'role_id' => $adminRole->id,
            ]
        );

        // Créer un utilisateur test
        Utilisateur::firstOrCreate(
            ['email' => 'user@campus.test'],
            [
                'nom' => 'Test User',
                'mot_de_passe' => \Illuminate\Support\Facades\Hash::make('password'),
                'email_verified_at' => now(),
                'filiere' => 'Informatique',
                'annee_etude' => 2024,
                'role_id' => $userRole->id,
            ]
        );
    }
}
