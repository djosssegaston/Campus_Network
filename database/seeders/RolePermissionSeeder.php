<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions d'abord
        $permissionData = [
            // Permissions de publication
            ['nom' => 'create_publication', 'description' => 'Créer une publication'],
            ['nom' => 'edit_publication', 'description' => 'Modifier une publication'],
            ['nom' => 'delete_publication', 'description' => 'Supprimer une publication'],
            ['nom' => 'view_publication', 'description' => 'Voir une publication'],

            // Permissions de groupe
            ['nom' => 'create_groupe', 'description' => 'Créer un groupe'],
            ['nom' => 'edit_groupe', 'description' => 'Modifier un groupe'],
            ['nom' => 'delete_groupe', 'description' => 'Supprimer un groupe'],
            ['nom' => 'manage_groupe_members', 'description' => 'Gérer les membres du groupe'],

            // Permissions de commentaire
            ['nom' => 'create_comment', 'description' => 'Créer un commentaire'],
            ['nom' => 'delete_comment', 'description' => 'Supprimer un commentaire'],

            // Permissions de modération
            ['nom' => 'moderate_content', 'description' => 'Modérer le contenu'],
            ['nom' => 'ban_user', 'description' => 'Bannir un utilisateur'],
            ['nom' => 'delete_user', 'description' => 'Supprimer un utilisateur'],

            // Permissions administrateur
            ['nom' => 'manage_roles', 'description' => 'Gérer les rôles'],
            ['nom' => 'manage_permissions', 'description' => 'Gérer les permissions'],
            ['nom' => 'view_analytics', 'description' => 'Voir les statistiques'],
            ['nom' => 'manage_system', 'description' => 'Gérer le système'],
        ];

        // Créer les permissions
        foreach ($permissionData as $permission) {
            Permission::firstOrCreate(
                ['nom' => $permission['nom']],
                ['description' => $permission['description']]
            );
        }

        // Récupérer toutes les permissions créées
        $allPermissions = Permission::all();
        $publishPermissions = $allPermissions->whereIn('nom', [
            'create_publication', 'edit_publication', 'delete_publication', 'view_publication'
        ])->pluck('id')->toArray();
        $groupPermissions = $allPermissions->whereIn('nom', [
            'create_groupe', 'edit_groupe', 'delete_groupe', 'manage_groupe_members'
        ])->pluck('id')->toArray();
        $commentPermissions = $allPermissions->whereIn('nom', [
            'create_comment', 'delete_comment'
        ])->pluck('id')->toArray();
        $modPermissions = $allPermissions->whereIn('nom', [
            'moderate_content', 'ban_user', 'delete_user', 'view_analytics'
        ])->pluck('id')->toArray();

        // 1. RÔLE ÉTUDIANT (niveau 1)
        $roleEtudiant = Role::firstOrCreate(
            ['slug' => 'etudiant'],
            [
                'nom' => 'Étudiant',
                'slug' => 'etudiant',
                'niveau' => 1,
            ]
        );
        $roleEtudiant->permissions()->sync(array_merge($publishPermissions, $groupPermissions, $commentPermissions));

        // 2. RÔLE ADMIN GROUPE (niveau 5)
        $roleAdminGroupe = Role::firstOrCreate(
            ['slug' => 'admin_groupe'],
            [
                'nom' => 'Admin Groupe',
                'slug' => 'admin_groupe',
                'niveau' => 5,
            ]
        );
        $roleAdminGroupe->permissions()->sync(array_merge($publishPermissions, $groupPermissions, $commentPermissions, $modPermissions));

        // 3. RÔLE MODÉRATEUR GLOBAL (niveau 7)
        $roleModerator = Role::firstOrCreate(
            ['slug' => 'moderateur_global'],
            [
                'nom' => 'Modérateur Global',
                'slug' => 'moderateur_global',
                'niveau' => 7,
            ]
        );
        $roleModerator->permissions()->sync($allPermissions->pluck('id')->toArray());

        // 4. RÔLE ADMINISTRATEUR (niveau 9)
        $roleAdmin = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'nom' => 'Administrateur',
                'slug' => 'admin',
                'niveau' => 9,
            ]
        );
        $roleAdmin->permissions()->sync($allPermissions->pluck('id')->toArray());

        // 5. RÔLE SUPER ADMIN (niveau 10)
        $roleSuperAdmin = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            [
                'nom' => 'Super Administrateur',
                'slug' => 'super_admin',
                'niveau' => 10,
            ]
        );
        $roleSuperAdmin->permissions()->sync($allPermissions->pluck('id')->toArray());

        // 6. RÔLE MODÉRATEUR GROUPE
        $roleModGroup = Role::firstOrCreate(
            ['slug' => 'moderateur_groupe'],
            [
                'nom' => 'Modérateur Groupe',
                'slug' => 'moderateur_groupe',
                'niveau' => 4,
            ]
        );
        $roleModGroup->permissions()->sync(array_merge($publishPermissions, $groupPermissions, $commentPermissions));
    }
}
