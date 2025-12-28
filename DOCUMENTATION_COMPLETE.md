# 📚 Campus Network - Documentation Complète

## 🎯 Vue d'ensemble

Campus Network est une plateforme sociale universitaire professionnelle construite avec **Laravel 11** et **Blade PHP**. Elle implémente un système de rôles et permissions granulaires permettant une expérience personnalisée pour chaque utilisateur selon ses permissions.

---

## 🏗️ Architecture du Système

### 1️⃣ Rôles Hiérarchiques (6 rôles)

| Rôle | Niveau | Permissions | Description |
|------|--------|-------------|-------------|
| **Étudiant** | 1 | 10 | Utilisateur de base - Publications, groupes, messages |
| **Modérateur Groupe** | 4 | 14 | Modération au niveau d'un groupe |
| **Admin Groupe** | 5 | 14 | Administration complète d'un groupe |
| **Modérateur Global** | 7 | 17 | Modération au niveau plateforme |
| **Administrateur** | 9 | 17 | Administration complète du système |
| **Super Admin** | 10 | 17 | Accès ultime (création des comptes admin) |

### 2️⃣ Permissions Granulaires (17 total)

#### 📝 Publications (4 perms)
- `create_publication` - Créer une publication
- `edit_publication` - Éditer les publications
- `delete_publication` - Supprimer les publications
- `view_publication` - Voir les publications

#### 👥 Groupes (4 perms)
- `create_groupe` - Créer un groupe
- `edit_groupe` - Éditer les groupes
- `delete_groupe` - Supprimer les groupes
- `manage_groupe_members` - Gérer les membres des groupes

#### 💬 Commentaires (2 perms)
- `create_comment` - Créer des commentaires
- `delete_comment` - Supprimer des commentaires

#### 🛡️ Modération (3 perms)
- `moderate_content` - Modérer le contenu
- `ban_user` - Bannir les utilisateurs
- `delete_user` - Supprimer les utilisateurs

#### ⚙️ Administration (4 perms)
- `manage_roles` - Gérer les rôles et permissions
- `manage_permissions` - Gérer les permissions
- `view_analytics` - Voir les statistiques
- `manage_system` - Maintenance du système

---

## 🎨 Interface Utilisateur Adaptée aux Rôles

### Dashboard Personnalisé
Chaque rôle affiche un dashboard unique avec:
- **Statistiques pertinentes** - Métriques adaptées au rôle
- **Actions disponibles** - Boutons et outils selon les permissions
- **Navigation contextuelle** - Menu latéral personnalisé

### 🔵 Étudiant
- Voir publications, groupes, messages
- Créer publications et participer
- Interface simple et épurée

### 🟠 Modérateur Groupe
- Réviser contenus du groupe
- Gérer les membres
- Voir rapports et statistiques

### 🟣 Admin Groupe
- Créer/éditer/supprimer groupes
- Gérer les rôles des membres
- Voir analytics détaillées

### 🔴 Modérateur Global
- Accès modération plateforme
- Gérer utilisateurs
- Voir tous les rapports

### 🟢 Administrateur
- Gestion complète utilisateurs
- Rôles & permissions
- Analytics avancées
- Maintenance système

### 🟣 Super Admin
- Accès TOTAL au système
- Créer comptes administrateur
- Tous les outils de maintenance

---

## 📁 Structure des Fichiers

```
resources/views/
├── welcome.blade.php              # Page d'accueil professionnelle
├── dashboard.blade.php            # Dashboard adapté aux rôles
├── feed.blade.php                 # Feed personnalisé
├── layouts/
│   └── app.blade.php             # Layout avec nav personnalisée
├── admin/
│   └── index.blade.php           # Panneau admin complet
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   └── ...
├── publications/
├── groupes/
├── messages/
└── profile/

app/
├── Models/
│   ├── User.php
│   ├── Utilisateur.php           # Modèle authentification
│   ├── Role.php                  # 6 rôles avec methods
│   ├── Permission.php            # 17 permissions
│   └── ...
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                 # Controllers auth (Blade)
│   │   ├── PublicationController.php
│   │   └── ...
│   ├── Middleware/
│   │   ├── CheckPermission.php    # Vérif permissions
│   │   └── RequireRole.php        # Vérif rôles
├── Helpers/
│   └── PermissionHelper.php       # Helpers permissions
├── Providers/
│   └── BladeServiceProvider.php   # Directives Blade
└── Console/
    └── Commands/
        ├── AssignRoleCommand.php
        ├── ListRolesCommand.php
        └── TestRolePermission.php

database/
├── migrations/
│   ├── ..._create_roles_table.php
│   ├── ...create_permissions_table.php
│   └── ...create_role_permission_table.php
└── seeders/
    └── RolePermissionSeeder.php
```

---

## 🚀 Comment Utiliser

### ✅ Se Connecter comme Super Admin

**Identifiants de test:**
```
Email: admin@campus.com
Mot de passe: Admin123!
```

### 🔐 Accéder aux Fonctionnalités par Rôle

```php
// Dans les controllers
if (auth()->user()->hasPermission('delete_user')) {
    // Afficher bouton suppression
}

if (auth()->user()->estAdmin()) {
    // Afficher panneau admin
}
```

### 🎭 Utiliser les Directives Blade

```blade
<!-- Vérifier permission -->
@canPerm('create_publication')
    <button>Créer Publication</button>
@endcanPerm

<!-- Vérifier rôle -->
@isRole('admin_groupe')
    <div>Admin groupe dashboard</div>
@endisRole

<!-- Vérifier admin -->
@isAdmin
    <a href="/admin">Admin Panel</a>
@endisAdmin
```

### 🎮 Assigner un Rôle

```bash
php artisan role:assign {user_id} {role_slug}

# Exemple:
php artisan role:assign 5 super_admin
```

### 📋 Lister les Rôles

```bash
php artisan role:list
```

### 🧪 Tester le Système

```bash
php artisan role:test
```

---

## 🔌 API Endpoints (Backend)

### Publications
```
POST   /api/publications              - Créer
GET    /api/publications/{id}         - Voir détail
PUT    /api/publications/{id}         - Éditer
DELETE /api/publications/{id}         - Supprimer
```

### Groupes
```
GET    /api/groupes                   - Lister
POST   /api/groupes                   - Créer
GET    /api/groupes/{id}              - Détail
PUT    /api/groupes/{id}              - Éditer
DELETE /api/groupes/{id}              - Supprimer
```

### Et bien d'autres...

---

## 🎨 Design System

### Couleurs par Rôle
- **Étudiant**: Bleu (`blue-600`)
- **Modérateur**: Orange (`orange-600`)
- **Admin Groupe**: Indigo (`indigo-600`)
- **Modérateur Global**: Rouge (`red-600`)
- **Administrateur**: Vert (`green-600`)
- **Super Admin**: Violet (`purple-600`)

### Composants
- Cards avec bordure latérale colorée
- Boutons d'action contextuels
- Dashboard avec statistiques
- Navigation personnalisée
- Icônes Font Awesome

---

## 📊 Dashboard par Rôle

### Étudiant
- Mes publications (5)
- Mes groupes (3)
- Mes messages (12)
- Actions: Créer publication, Découvrir groupes

### Modérateur Groupe
- Groupes modérés (2)
- Contenus à modérer (4)
- Rapports reçus (1)
- Membres actifs (145)

### Admin Groupe
- Mes groupes (3)
- Total membres (342)
- Publications (87)
- Taux engagement (64%)

### Modérateur Global
- Utilisateurs bannis (8)
- Alertes actives (12)
- Contenus supprimés (23)
- Rapports résolus (45)
- Santé plateforme (92%)

### Administrateur
- Utilisateurs total (1,247)
- Groupes total (89)
- Publications (3,542)
- Utilisateurs actifs (892)
- Santé système (98%)

### Super Admin
- Tous les stats + Plus
- 6 rôles disponibles
- 17 permissions visibles
- Uptime (99.8%)
- Centre de contrôle complet

---

## 🔄 Flux d'Authentification

```
1. Utilisateur accède /login
   ↓
2. Vérifie identifiants
   ↓
3. Charge Utilisateur + Rôle + Permissions
   ↓
4. Redirige vers dashboard approprié
   ↓
5. Interface adaptée au rôle
```

---

## 🛠️ Commandes Artisan Utiles

```bash
# Afficher tous les rôles
php artisan role:list

# Assigner un rôle à un utilisateur
php artisan role:assign {user_id} {role_slug}

# Tester le système de rôles
php artisan role:test

# Réinitialiser les rôles et permissions
php artisan db:seed --class=RolePermissionSeeder

# Vérifier la santé du système
php artisan role:test
```

---

## 🎯 Prochaines Étapes

1. ✅ Dashboard personnalisés par rôle
2. ✅ Navigation adaptée
3. ✅ Permissions granulaires
4. ⏳ Intégrations real-time (WebSocket)
5. ⏳ Notifications avancées
6. ⏳ Mobile app
7. ⏳ API documentation (Swagger)

---

## 📞 Support

Pour toute question sur:
- **Les rôles**: Voir `app/Models/Role.php`
- **Les permissions**: Voir `app/Models/Permission.php`
- **Les vues**: Voir `resources/views/`
- **Les helpers**: Voir `app/Helpers/PermissionHelper.php`

---

## 📄 License

Campus Network © 2025. Tous droits réservés.
