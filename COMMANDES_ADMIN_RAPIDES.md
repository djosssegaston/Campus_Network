# COMMANDES RAPIDES - ADMINISTRATION CAMPUS NETWORK

## 🚀 Démarrage du Serveur

```bash
# Démarrer Laravel
cd c:\Users\HP\Campus_Network
php artisan serve --port=8000

# Dans un autre terminal: Compiler les assets
npm run dev
```

## 🔧 Installation Initiale

### 1. Première fois après clone
```bash
# Installation des dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate --force
php artisan db:seed --class=PermissionSeeder

# Nettoyage
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 2. Utiliser le script d'installation
```bash
php install.php
```

## 👤 Authentification Admin

### Créer un utilisateur admin manuellement
```php
// Accéder à Tinker
php artisan tinker

// Créer un rôle admin (s'il n'existe pas)
$admin = \App\Models\Role::create(['nom' => 'Admin', 'slug' => 'admin']);

// Créer un utilisateur admin
$user = \App\Models\Utilisateur::create([
    'nom' => 'Admin',
    'email' => 'admin@campus.local',
    'password' => bcrypt('password123'),
    'role_id' => $admin->id,
]);

// Vérifier
$user->load('role');
exit
```

## 🗂️ Navigation Admin

### Routes Principales
```
/admin                          → Dashboard principal
/admin/users                    → Gestion utilisateurs
/admin/roles                    → Gestion rôles
/admin/permissions              → Gestion permissions
/admin/settings                 → Paramètres système
/admin/moderation               → Tableau modération
/admin/analytics                → Statistiques
/admin/maintenance              → Outils maintenance
```

## 🔍 Vérifications Utiles

### Vérifier les routes
```bash
php artisan route:list | grep admin
php artisan route:list | grep -E "(admin|users|roles)"
```

### Vérifier les migrations
```bash
php artisan migrate:status
```

### Vérifier les permissions
```bash
php artisan tinker
\App\Models\Permission::all();
\App\Models\Role::with('permissions')->get();
exit
```

## 🧹 Maintenance

### Nettoyer les caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Réinitialiser la base de données
```bash
# ⚠️ ATTENTION: Supprime toutes les données
php artisan migrate:fresh
php artisan db:seed --class=PermissionSeeder
```

### Vérifier la santé du système
```bash
php artisan route:list
php artisan config:show
php artisan about
```

## 📊 Gestion des Utilisateurs

### Créer un utilisateur
```php
php artisan tinker

$user = \App\Models\Utilisateur::create([
    'nom' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'role_id' => 2, // User role
]);

exit
```

### Attribuer un rôle à un utilisateur
```php
php artisan tinker

$user = \App\Models\Utilisateur::find(1);
$user->update(['role_id' => 1]); // 1 = Admin

exit
```

### Bannir un utilisateur
```php
php artisan tinker

$user = \App\Models\Utilisateur::find(1);
$user->update([
    'is_banned' => true,
    'ban_reason' => 'Violation des règles',
    'banned_at' => now()
]);

exit
```

### Débannir un utilisateur
```php
php artisan tinker

$user = \App\Models\Utilisateur::find(1);
$user->update([
    'is_banned' => false,
    'ban_reason' => null,
    'banned_at' => null
]);

exit
```

## 🔐 Gestion des Permissions

### Créer une permission
```php
php artisan tinker

$permission = \App\Models\Permission::create([
    'nom' => 'Nouvelle Permission',
    'slug' => 'new_permission',
    'description' => 'Description'
]);

exit
```

### Assigner une permission à un rôle
```php
php artisan tinker

$role = \App\Models\Role::find(1); // Admin
$permission = \App\Models\Permission::where('slug', 'delete_users')->first();

$role->permissions()->attach($permission->id);

exit
```

## 📝 Gestion du Contenu

### Signaler un contenu
```php
php artisan tinker

$signalement = \App\Models\Signalement::create([
    'utilisateur_id' => 1,
    'publication_id' => 1,
    'type' => 'spam',
    'raison' => 'Publication spam',
    'status' => 'pending'
]);

exit
```

### Masquer une publication
```php
php artisan tinker

$publication = \App\Models\Publication::find(1);
$publication->update(['is_hidden' => true]);

exit
```

### Flagger une publication
```php
php artisan tinker

$publication = \App\Models\Publication::find(1);
$publication->update(['is_flagged' => true]);

exit
```

## 📊 Statistiques

### Compter les utilisateurs
```php
php artisan tinker

\App\Models\Utilisateur::count();
\App\Models\Utilisateur::where('is_active', true)->count();
\App\Models\Utilisateur::where('is_banned', true)->count();

exit
```

### Compter les publications
```php
php artisan tinker

\App\Models\Publication::count();
\App\Models\Publication::where('is_hidden', true)->count();
\App\Models\Publication::where('is_flagged', true)->count();

exit
```

### Compter les groupes
```php
php artisan tinker

\App\Models\Groupe::count();
\App\Models\Groupe::with('utilisateurs')->get();

exit
```

## 🔄 Tâches Planifiées (Scheduling)

### Ajouter une tâche planifiée
```php
// Dans App\Console\Kernel.php

protected function schedule(Schedule $schedule)
{
    // Nettoyer les comptes inactifs chaque jour
    $schedule->command('users:cleanup-inactive')->daily();
    
    // Nettoyer les fichiers orphelins chaque semaine
    $schedule->command('files:cleanup')->weekly();
}
```

## 🧪 Tests

### Tester une route
```bash
# GET
curl -I http://localhost:8000/admin

# POST avec token CSRF (depuis le formulaire)
curl -X POST http://localhost:8000/admin/users \
  -H "X-CSRF-TOKEN: token" \
  -d "nom=Test&email=test@example.com"
```

### Tester avec PHPUnit
```bash
php artisan test
php artisan test --filter=AdminTest
```

## 📱 API Endpoints (Si disponibles)

```bash
# Récupérer les utilisateurs
curl http://localhost:8000/api/admin/users

# Créer un utilisateur
curl -X POST http://localhost:8000/api/admin/users \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer token" \
  -d '{"nom":"Test","email":"test@example.com"}'
```

## 🐛 Debugging

### Activer le debug mode
```bash
# Dans .env
APP_DEBUG=true
APP_ENV=local
```

### Voir les queries SQL
```php
php artisan tinker

\DB::enableQueryLog();
\App\Models\Utilisateur::all();
\DB::getQueryLog();

exit
```

### Logs
```bash
# Voir les logs en temps réel
tail -f storage/logs/laravel.log

# Ou via l'admin
http://localhost:8000/admin/settings/logs
```

## 🔄 Git Commands

```bash
# Vérifier le statut
git status

# Ajouter les fichiers
git add .

# Committer
git commit -m "Implémentation des 7 fonctionnalités admin"

# Pousser
git push origin main
```

## 📚 Documentation

- Lire: `IMPLEMENTATION_7_FONCTIONNALITES.md` (Guide complet)
- Lire: `RESUME_FINAL_IMPLEMENTATION.md` (Résumé)
- Consulter: `routes/web.php` (Toutes les routes)

## ✅ Checklist de Déploiement

- [ ] Base de données créée
- [ ] Migrations exécutées
- [ ] Permissions créées
- [ ] Utilisateur admin créé
- [ ] Caches nettoyés
- [ ] Variables .env configurées
- [ ] SSL configuré (production)
- [ ] Email configuré (production)
- [ ] Backups configurés
- [ ] Monitoring activé

## 📞 Support

Pour les erreurs:
1. Vérifiez les logs: `storage/logs/laravel.log`
2. Exécutez: `php artisan route:clear && php artisan cache:clear`
3. Vérifiez les migrations: `php artisan migrate:status`
4. Consultez la documentation

---

**Dernière mise à jour**: Janvier 2024
**Version**: 1.0.0
