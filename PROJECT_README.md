# 🎓 Campus Network - Plateforme Sociale Universitaire

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-green.svg)
![Laravel](https://img.shields.io/badge/Laravel-11-red.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

> Une plateforme complète de réseautage social universitaire construite avec Laravel 11, Blade PHP, et Tailwind CSS. Implémente un système de rôles et permissions granulaires pour une gestion flexible des accès utilisateur.

---

## 🌟 Caractéristiques Principales

### 🔐 Système de Rôles & Permissions
- **6 Rôles hiérarchiques** (Étudiant → Super Admin)
- **17 Permissions granulaires**
- Gestion fine des accès utilisateur
- Middleware de vérification automatique

### 🎨 Interface Responsive
- **Dashboard personnalisé** par rôle
- **Navigation adaptée** aux permissions
- **Design moderne** avec Tailwind CSS
- **Mobile-friendly** et accessible

### 📱 Fonctionnalités
- ✅ Authentification sécurisée (Breeze)
- ✅ Publications et Feed
- ✅ Groupes et Communautés
- ✅ Messagerie Privée
- ✅ Système de Notifications
- ✅ Commentaires et Réactions
- ✅ Modération de Contenu
- ✅ Analytics Avancées

---

## 📋 Architecture

### Rôles

| Rôle | Niveau | Permissions | Description |
|------|--------|-------------|-------------|
| Étudiant | 1 | 10 | Utilisateur standard |
| Modérateur Groupe | 4 | 14 | Modération groupe |
| Admin Groupe | 5 | 14 | Admin groupe |
| Modérateur Global | 7 | 17 | Modération plateforme |
| Administrateur | 9 | 17 | Admin système |
| Super Admin | 10 | 17 | Contrôle total |

### Permissions

**Publications** (4): create, edit, delete, view
**Groupes** (4): create, edit, delete, manage_members
**Commentaires** (2): create, delete
**Modération** (3): moderate_content, ban_user, delete_user
**Administration** (4): manage_roles, manage_permissions, view_analytics, manage_system

---

## 🚀 Installation Rapide

### Prérequis
- PHP 8.2+
- Composer
- Node.js 16+
- MySQL 8.0+

### Étapes

```bash
# 1. Cloner le projet
git clone https://github.com/yourusername/campus-network.git
cd campus-network

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Database
php artisan migrate --seed

# 5. Build assets
npm run build

# 6. Démarrer
php artisan serve
```

### Accès Initial
```
Email: admin@campus.com
Mot de passe: Admin123!
URL: http://localhost:8000
```

---

## 📂 Structure du Projet

```
├── app/
│   ├── Models/              # Modèles (Role, Permission, User, etc)
│   ├── Http/
│   │   ├── Controllers/     # Controllers (Auth, Publications, etc)
│   │   ├── Middleware/      # CheckPermission, RequireRole
│   │   └── Requests/        # Form Requests
│   ├── Helpers/             # PermissionHelper
│   ├── Console/
│   │   └── Commands/        # Artisan commands (role:list, role:assign)
│   └── Providers/           # Blade directives
├── database/
│   ├── migrations/          # 20 migrations
│   └── seeders/             # RolePermissionSeeder
├── resources/
│   ├── views/               # 20 Blade templates
│   │   ├── dashboard.blade.php     # Dashboard adapté rôles
│   │   ├── feed.blade.php
│   │   ├── admin/
│   │   ├── auth/
│   │   ├── publications/
│   │   ├── groupes/
│   │   ├── messages/
│   │   └── layouts/
│   ├── css/                 # Tailwind CSS
│   └── js/                  # Alpine.js
├── routes/
│   ├── web.php              # Routes web (Blade)
│   └── api.php              # API routes (30+ endpoints)
├── tests/                   # Tests unitaires
└── public/                  # Assets compilés
```

---

## 🎯 Utilisation Principale

### Vérifier les Permissions

```php
// Dans les controllers
if (auth()->user()->hasPermission('delete_publication')) {
    // Autoriser suppression
}

if (auth()->user()->estAdmin()) {
    // Afficher panneau admin
}

if (auth()->user()->role->slug === 'moderateur_global') {
    // Afficher outils modération
}
```

### Directives Blade

```blade
<!-- Vérifier permission -->
@canPerm('create_publication')
    <button>Créer Publication</button>
@endcanPerm

<!-- Vérifier rôle -->
@isRole('admin_groupe')
    <div>Admin dashboard</div>
@endisRole

<!-- Vérifier admin -->
@isAdmin
    <a href="/admin">Admin Panel</a>
@endisAdmin

<!-- Vérifier édition -->
@canEdit($userId)
    <button>Éditer</button>
@endcanEdit
```

### Middleware

```php
// Dans routes/web.php
Route::post('/users/{id}/ban', 
    [UserController::class, 'ban'])
    ->middleware('can:ban_user');

Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('role:administrateur');
```

---

## 🛠️ Commandes Disponibles

```bash
# Lister les rôles
php artisan role:list

# Assigner un rôle
php artisan role:assign {user_id} {role_slug}

# Tester le système
php artisan role:test

# Réinitialiser rôles
php artisan db:seed --class=RolePermissionSeeder

# Créer admin
php artisan tinker
> Utilisateur::create(['email' => '...', ...])
```

---

## 📊 Dashboard par Rôle

### 👨‍🎓 Étudiant
- Voir mes publications (5)
- Mes groupes (3)
- Mes messages (12)
- Créer publication
- Découvrir groupes

### 🟠 Modérateur Groupe
- Groupes modérés (2)
- Contenus à modérer (4)
- Rapports reçus (1)
- Réviser contenus
- Gérer membres

### 🟣 Admin Groupe
- Mes groupes (3)
- Total membres (342)
- Publications (87)
- Taux engagement (64%)
- Paramètres groupe

### 🔴 Modérateur Global
- Utilisateurs bannis (8)
- Alertes actives (12)
- Contenus supprimés (23)
- Rapports résolus (45)
- Centre de contrôle

### 🟢 Administrateur
- Utilisateurs total (1,247)
- Groupes (89)
- Publications (3,542)
- Utilisateurs actifs (892)
- Gestion complète

### 🟣 Super Admin
- Tous les stats
- 6 rôles disponibles
- 17 permissions
- Uptime 99.8%
- Contrôle total

---

## 🔌 API REST Endpoints

### Publications
```
POST   /api/publications              # Créer
GET    /api/publications/{id}         # Détail
PUT    /api/publications/{id}         # Éditer
DELETE /api/publications/{id}         # Supprimer
```

### Groupes
```
GET    /api/groupes                   # Lister
POST   /api/groupes                   # Créer
GET    /api/groupes/{id}              # Détail
PUT    /api/groupes/{id}              # Éditer
DELETE /api/groupes/{id}              # Supprimer
```

### Utilisateurs (Admin only)
```
GET    /api/users                     # Lister
POST   /api/users                     # Créer
PUT    /api/users/{id}                # Éditer
DELETE /api/users/{id}                # Supprimer
POST   /api/users/{id}/ban            # Bannir
```

---

## 🎨 Palette Couleurs

- **Étudiant**: Bleu `#2563eb`
- **Modérateur**: Orange `#ea580c`
- **Admin Groupe**: Indigo `#4f46e5`
- **Modérateur Global**: Rouge `#dc2626`
- **Administrateur**: Vert `#16a34a`
- **Super Admin**: Violet `#9333ea`

---

## 🔒 Sécurité

- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ Hachage des mots de passe (bcrypt)
- ✅ Email verification
- ✅ Rate limiting
- ✅ Permission middleware
- ✅ Role-based access control

---

## 📈 Performance

- **CSS compilé**: 54 KB (9.18 KB gzip)
- **JS compilé**: 44 KB (16.32 KB gzip)
- **Total packages**: 113
- **Database queries**: Optimisées avec Eloquent relations
- **Caching**: Redis ready

---

## 🧪 Tests

```bash
# Exécuter les tests
php artisan test

# Tests avec coverage
php artisan test --coverage

# Tests spécifiques
php artisan test tests/Feature/RoleTest.php
```

---

## 📚 Documentation

- [DOCUMENTATION_COMPLETE.md](./DOCUMENTATION_COMPLETE.md) - Guide complet du système
- [API_ENDPOINTS.md](./API_ENDPOINTS.md) - Documentation API (à créer)
- [ROLES_PERMISSIONS_GUIDE.md](./ROLES_PERMISSIONS_GUIDE.md) - Guide rôles/permissions

---

## 🤝 Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

---

## 📄 License

Ce projet est sous license MIT. Voir [LICENSE](LICENSE) pour détails.

---

## 👥 Auteurs

- **Développeur**: Campus Network Team
- **Année**: 2025
- **Contact**: support@campusnetwork.com

---

## 🙏 Remerciements

- Laravel Community
- Tailwind CSS
- Font Awesome
- Tous les contributeurs

---

## 📞 Support

Pour toute question ou problème:
1. Consultez la [Documentation](./DOCUMENTATION_COMPLETE.md)
2. Ouvrez une [Issue](https://github.com/yourusername/campus-network/issues)
3. Contactez: support@campusnetwork.com

---

**Made with ❤️ for students everywhere**
