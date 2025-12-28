<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use App\Models\Role;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (usually admin@example.com or the main user)
        $admin = Utilisateur::where('email', 'admin@example.com')->first() 
                  ?? Utilisateur::first();
        
        if ($admin) {
            // Get admin role
            $adminRole = Role::where('slug', 'admin')->first();
            
            if ($adminRole) {
                // Assign admin role
                $admin->role_id = $adminRole->id;
                $admin->save();
                
                echo "✓ Admin role assigned to user: {$admin->email}\n";
            }
        }
    }
}
