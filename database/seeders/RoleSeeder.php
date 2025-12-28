<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(
            ['nom' => 'admin'],
            ['description' => 'Administrateur du système']
        );

        Role::firstOrCreate(
            ['nom' => 'moderateur'],
            ['description' => 'Modérateur de contenu']
        );

        Role::firstOrCreate(
            ['nom' => 'utilisateur'],
            ['description' => 'Utilisateur standard']
        );
    }
}