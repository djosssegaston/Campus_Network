<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Utilisateur;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Utilisateur::updateOrCreate(
            ['email' => 'test@campus.local'],
            [
                'nom' => 'Test User',
                'mot_de_passe' => 'Test1234!',
                'role_id' => 2,
                'filiere' => 'Informatique',
                'annee_etude' => 1,
            ]
        );

        $this->command->info('✓ Test user created/updated');
    }
}
