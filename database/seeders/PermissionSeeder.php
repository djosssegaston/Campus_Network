<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions de base
        $permissions = [
            // Utilisateurs
            ['nom' => 'Voir les utilisateurs', 'slug' => 'view_users', 'description' => 'Voir la liste des utilisateurs'],
            ['nom' => 'Éditer les utilisateurs', 'slug' => 'edit_users', 'description' => 'Éditer les informations des utilisateurs'],
            ['nom' => 'Supprimer les utilisateurs', 'slug' => 'delete_users', 'description' => 'Supprimer les utilisateurs'],
            
            // Rôles et permissions
            ['nom' => 'Voir les rôles', 'slug' => 'view_roles', 'description' => 'Voir les rôles'],
            ['nom' => 'Créer les rôles', 'slug' => 'create_roles', 'description' => 'Créer de nouveaux rôles'],
            ['nom' => 'Éditer les rôles', 'slug' => 'edit_roles', 'description' => 'Éditer les rôles'],
            ['nom' => 'Supprimer les rôles', 'slug' => 'delete_roles', 'description' => 'Supprimer les rôles'],
            
            // Permissions
            ['nom' => 'Voir les permissions', 'slug' => 'view_permissions', 'description' => 'Voir les permissions'],
            ['nom' => 'Créer les permissions', 'slug' => 'create_permissions', 'description' => 'Créer de nouvelles permissions'],
            ['nom' => 'Éditer les permissions', 'slug' => 'edit_permissions', 'description' => 'Éditer les permissions'],
            ['nom' => 'Supprimer les permissions', 'slug' => 'delete_permissions', 'description' => 'Supprimer les permissions'],
            
            // Paramètres système
            ['nom' => 'Voir les paramètres système', 'slug' => 'view_system_settings', 'description' => 'Voir les paramètres système'],
            ['nom' => 'Éditer les paramètres système', 'slug' => 'update_system_settings', 'description' => 'Éditer les paramètres système'],
            
            // Modération
            ['nom' => 'Voir la modération', 'slug' => 'view_moderation', 'description' => 'Accéder au tableau de bord de modération'],
            ['nom' => 'Modérer le contenu', 'slug' => 'moderate_content', 'description' => 'Modérer le contenu'],
            ['nom' => 'Bannir les utilisateurs', 'slug' => 'ban_users', 'description' => 'Bannir les utilisateurs'],
            
            // Analytics
            ['nom' => 'Voir les analytics', 'slug' => 'view_analytics', 'description' => 'Voir les analytics'],
            
            // Maintenance
            ['nom' => 'Voir la maintenance', 'slug' => 'view_maintenance', 'description' => 'Accéder aux outils de maintenance'],
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['slug' => $permission['slug']], $permission);
        }
        
        // Créer les rôles avec les permissions appropriées
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['nom' => 'Administrateur', 'description' => 'Administrateur du système']
        );
        
        $userRole = Role::firstOrCreate(
            ['slug' => 'user'],
            ['nom' => 'Utilisateur', 'description' => 'Utilisateur standard']
        );
        
        $moderatorRole = Role::firstOrCreate(
            ['slug' => 'moderator'],
            ['nom' => 'Modérateur', 'description' => 'Modérateur du contenu']
        );
        
        // Attribuer toutes les permissions à l'admin
        $adminPermissions = Permission::all()->pluck('id')->toArray();
        $adminRole->permissions()->sync($adminPermissions);
        
        // Modérateur: modération uniquement
        $moderatorPermissions = Permission::whereIn('slug', [
            'view_moderation',
            'moderate_content',
            'view_analytics',
            'view_users',
        ])->pluck('id')->toArray();
        $moderatorRole->permissions()->sync($moderatorPermissions);
        
        // Utilisateur: pas de permissions spéciales
        $userRole->permissions()->sync([]);
    }
}
