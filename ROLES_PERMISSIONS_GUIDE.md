# 🔐 Système de Rôles et Permissions - Documentation

## Vue d'ensemble

Campus Network dispose maintenant d'un **système complet de rôles et permissions** qui permet de distinguer les utilisateurs par:
- **Rôle assigné** (6 rôles disponibles)
- **Permissions associées** (17 permissions)
- **Hiérarchie** (niveaux de priorité)

---

## 📋 Rôles Disponibles

| Rôle | Slug | Niveau | Permissions |
|------|------|--------|-------------|
| **Étudiant** | `etudiant` | 1 | Publication, Groupe, Commentaire (basiques) |
| **Modérateur Groupe** | `moderateur_groupe` | 4 | Publication, Groupe, Modération |
| **Admin Groupe** | `admin_groupe` | 5 | Publication, Groupe, Modération complète |
| **Modérateur Global** | `moderateur_global` | 7 | Tous sauf administration |
| **Administrateur** | `admin` ou `administrateur` | 9 | **TOUTES les permissions** |
| **Super Admin** | `super_admin` | 10 | **TOUTES les permissions** |

---

## 🔑 Permissions Disponibles

### Publications
- `create_publication` - Créer une publication
- `edit_publication` - Modifier sa publication
- `delete_publication` - Supprimer sa publication
- `view_publication` - Voir les publications

### Groupes
- `create_groupe` - Créer un groupe
- `edit_groupe` - Modifier un groupe
- `delete_groupe` - Supprimer un groupe
- `manage_groupe_members` - Gérer les membres du groupe

### Commentaires
- `create_comment` - Créer un commentaire
- `delete_comment` - Supprimer un commentaire

### Modération
- `moderate_content` - Modérer le contenu
- `ban_user` - Bannir un utilisateur
- `delete_user` - Supprimer un utilisateur

### Administration
- `manage_roles` - Gérer les rôles
- `manage_permissions` - Gérer les permissions
- `view_analytics` - Voir les statistiques
- `manage_system` - Gérer le système

---

## 🛠️ Utilisation dans le Code

### 1. Vérifier si un utilisateur est administrateur

```php
// Dans un contrôleur
if (auth()->user()->estAdmin()) {
    // L'utilisateur est admin
}

// Dans une vue Blade
@if(auth()->user()->estAdmin())
    <a href="/admin">Panel Admin</a>
@endif
```

### 2. Vérifier si un utilisateur est modérateur

```php
if (auth()->user()->estModerateurGlobal()) {
    // Afficher les outils de modération
}
```

### 3. Vérifier une permission spécifique

```php
// Vérifier une permission
if (auth()->user()->hasPermission('create_publication')) {
    // L'utilisateur peut créer une publication
}

// Vérifier plusieurs permissions (au moins une)
if (auth()->user()->hasAnyPermission(['create_publication', 'edit_publication'])) {
    // L'utilisateur peut créer OU modifier
}

// Vérifier toutes les permissions
if (auth()->user()->hasAllPermissions(['create_publication', 'delete_publication'])) {
    // L'utilisateur peut créer ET supprimer
}
```

### 4. Protéger des routes avec middleware

```php
// Dans routes/api.php

// Protection admin
Route::middleware('admin')->group(function () {
    Route::get('/admin/stats', [AdminController::class, 'stats']);
});

// Protection permission (futur)
Route::middleware('permission:create_publication')->group(function () {
    Route::post('/publications', [PublicationController::class, 'store']);
});
```

### 5. Dans les vues Blade

```blade
{{-- Vérifier un rôle --}}
@if(auth()->user()->estAdmin())
    <div class="admin-panel">
        Vous êtes administrateur
    </div>
@endif

{{-- Vérifier une permission --}}
@if(auth()->user()->hasPermission('moderate_content'))
    <button onclick="openModerationPanel()">Modération</button>
@endif

{{-- Vérifier le rôle directement --}}
@if(auth()->user()->role && auth()->user()->role->slug === 'admin')
    Admin content
@endif
```

---

## 📱 Commandes Utiles

### Lister tous les rôles et permissions

```bash
php artisan role:list
```

Affiche tous les rôles avec leurs permissions respectives.

### Assigner un rôle à un utilisateur

```bash
# Rendre un utilisateur admin
php artisan role:assign 1 admin

# Rendre un utilisateur modérateur
php artisan role:assign 2 moderateur_global

# Rendre un utilisateur étudiant
php artisan role:assign 3 etudiant
```

### Via Tinker

```bash
php artisan tinker

# Assigner un rôle
$user = User::find(1);
$user->role_id = Role::where('slug', 'admin')->first()->id;
$user->save();

# Vérifier les permissions
$user->hasPermission('create_publication');
$user->estAdmin();

# Voir tous les rôles
Role::all();

# Voir les permissions d'un rôle
Role::find(1)->permissions()->get();
```

---

## 🔄 Architecture des Rôles

```
┌─────────────────────────────────────┐
│         Utilisateur                  │
│  - id                               │
│  - email                            │
│  - role_id (Foreign Key)            │
└────────────┬────────────────────────┘
             │ belongs to
             ▼
┌─────────────────────────────────────┐
│            Rôle                      │
│  - id                               │
│  - nom (Administrateur)             │
│  - slug (admin)                     │
│  - niveau (9)                       │
│  - permissions (JSON)               │
└────────────┬────────────────────────┘
             │ has many
             ├──► role_permission pivot
             │
             ▼
┌─────────────────────────────────────┐
│         Permission                   │
│  - id                               │
│  - nom (create_publication)         │
│  - description                      │
└─────────────────────────────────────┘
```

---

## 🔐 Hiérarchie et Comparaison

```php
$userRole = auth()->user()->role;
$otherRole = Role::find(5);

// Comparer les niveaux
if ($userRole->niveau >= $otherRole->niveau) {
    // L'utilisateur a un rôle >= au rôle donné
}

// Utiliser isHigherThan()
if ($userRole->isHigherThan($otherRole)) {
    // L'utilisateur peut gérer cet utilisateur
}
```

---

## ✅ Vérification dans les Contrôleurs API

```php
// app/Http/Controllers/Api/PublicationController.php

public function store(Request $request)
{
    // Vérifier la permission
    if (!auth()->user()->hasPermission('create_publication')) {
        return response()->json(['message' => 'Permission refusée'], 403);
    }
    
    // Créer la publication
    $publication = auth()->user()->publications()->create(
        $request->validate([...])
    );
    
    return response()->json(['data' => $publication], 201);
}

public function destroy($id)
{
    $publication = Publication::findOrFail($id);
    
    // Vérifier: propriétaire OU admin
    if ($publication->user_id !== auth()->id() && !auth()->user()->estAdmin()) {
        return response()->json(['message' => 'Non autorisé'], 403);
    }
    
    $publication->delete();
    return response()->json(['message' => 'Supprimée']);
}
```

---

## 🎯 Cas d'usage Courants

### Cas 1: Autoriser seulement les admins

```php
if (!auth()->user()->estAdmin()) {
    abort(403, 'Accès refusé');
}
```

### Cas 2: Autoriser le propriétaire ou un modérateur

```php
$canEdit = (
    $publication->user_id === auth()->id() ||
    auth()->user()->hasPermission('moderate_content')
);

if (!$canEdit) {
    abort(403);
}
```

### Cas 3: Afficher un bouton selon les permissions

```blade
@if(auth()->user()->hasPermission('delete_publication'))
    <button onclick="deletePublication({{ $publication->id }})">
        Supprimer
    </button>
@endif
```

### Cas 4: Différencier le contenu par rôle

```blade
@if(auth()->user()->role?->slug === 'admin')
    <div class="admin-content">...</div>
@elseif(auth()->user()->role?->slug === 'moderateur_global')
    <div class="moderator-content">...</div>
@else
    <div class="student-content">...</div>
@endif
```

---

## 📝 Ajouter une Nouvelle Permission

```php
// 1. Créer la permission en base de données
php artisan tinker
Permission::create(['nom' => 'new_permission', 'description' => 'Description']);

// 2. Assigner à des rôles
$role = Role::where('slug', 'admin')->first();
$role->permissions()->attach(Permission::where('nom', 'new_permission')->first());

// 3. Utiliser dans le code
if (auth()->user()->hasPermission('new_permission')) {
    // Code
}
```

---

## 🔄 Synchroniser les Permissions d'un Rôle

```php
php artisan tinker

$role = Role::find(1); // Admin role

// Ajouter des permissions spécifiques
$permissions = Permission::whereIn('nom', [
    'create_publication',
    'edit_publication',
    'delete_publication'
])->get();

$role->permissions()->sync($permissions->pluck('id'));
```

---

## 🎓 Résumé

| Tâche | Code |
|-------|------|
| Vérifier admin | `auth()->user()->estAdmin()` |
| Vérifier permission | `auth()->user()->hasPermission('nom')` |
| Assigner rôle | `user->role_id = Role::where('slug', 'admin')->first()->id` |
| Lister rôles | `php artisan role:list` |
| Vérifier modérateur | `auth()->user()->estModerateurGlobal()` |
| Vérifier propriétaire | `$model->user_id === auth()->id()` |

---

✅ **Système de rôles et permissions entièrement configuré et prêt à l'emploi!**
