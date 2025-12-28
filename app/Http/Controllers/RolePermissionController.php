<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RolePermissionController extends Controller
{
    /**
     * Afficher les rôles
     */
    public function indexRoles(Request $request): View
    {
        $this->authorize('viewAnyRole', Role::class);
        
        $roles = Role::with('permissions')->paginate(15);
        
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Afficher le formulaire de création de rôle
     */
    public function createRole(): View
    {
        $this->authorize('createRole', Role::class);
        
        $permissions = Permission::all();
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Créer un nouveau rôle
     */
    public function storeRole(Request $request): RedirectResponse
    {
        $this->authorize('createRole', Role::class);

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:roles',
            'slug' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array|exists:permissions,id',
        ]);

        $role = Role::create($validated);
        
        if ($request->filled('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès');
    }

    /**
     * Éditer un rôle
     */
    public function editRole(Role $role): View
    {
        $this->authorize('updateRole', $role);
        
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Mettre à jour un rôle
     */
    public function updateRole(Request $request, Role $role): RedirectResponse
    {
        $this->authorize('updateRole', $role);

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:roles,nom,' . $role->id,
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array|exists:permissions,id',
        ]);

        $role->update($validated);
        
        if ($request->filled('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('roles.index')->with('success', 'Rôle mis à jour avec succès');
    }

    /**
     * Supprimer un rôle
     */
    public function destroyRole(Role $role): RedirectResponse
    {
        $this->authorize('deleteRole', $role);

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rôle supprimé avec succès');
    }

    /**
     * Afficher les permissions
     */
    public function indexPermissions(Request $request): View
    {
        $this->authorize('viewAnyPermission', Permission::class);
        
        $permissions = Permission::paginate(20);
        
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Créer une permission
     */
    public function createPermission(): View
    {
        $this->authorize('createPermission', Permission::class);
        
        return view('admin.permissions.create');
    }

    /**
     * Stocker une permission
     */
    public function storePermission(Request $request): RedirectResponse
    {
        $this->authorize('createPermission', Permission::class);

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:permissions',
            'slug' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
        ]);

        Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission créée avec succès');
    }

    /**
     * Éditer une permission
     */
    public function editPermission(Permission $permission): View
    {
        $this->authorize('updatePermission', $permission);
        
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Mettre à jour une permission
     */
    public function updatePermission(Request $request, Permission $permission): RedirectResponse
    {
        $this->authorize('updatePermission', $permission);

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:permissions,nom,' . $permission->id,
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission mise à jour avec succès');
    }

    /**
     * Supprimer une permission
     */
    public function destroyPermission(Permission $permission): RedirectResponse
    {
        $this->authorize('deletePermission', $permission);

        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission supprimée avec succès');
    }
}
