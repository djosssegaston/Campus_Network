# Campus Network - Plateforme Sociale Académique

**Version:** 2.0 - Blade PHP Edition
**Date:** 24 Décembre 2025
**Migration:** ✅ React → Blade PHP Complétée

---

## 📱 À Propos du Projet

Campus Network est une plateforme social media destinée aux communautés académiques (étudiants, enseignants). Elle permet aux utilisateurs de:

- 📝 **Partager des publications** avec la communauté
- 👥 **Rejoindre des groupes** thématiques
- 💬 **Communiquer** via messagerie privée
- 🔔 **Recevoir des notifications** en temps réel
- 📊 **Voir ses statistiques** personnelles

---

## 🏗️ Architecture Technique

### Stack Technologique

**Backend:**
- PHP 8.2+
- Laravel 11.x
- MySQL/SQLite

**Frontend:**
- **Blade PHP** (templates) ← Nouveau!
- **Tailwind CSS** (design)
- **Alpine.js** (interactivité)
- **Axios** (API REST)

**DevOps:**
- Vite (bundler)
- npm (package manager)
- Laravel Artisan (CLI)

### Structure du Projet

```
Campus Network/
├── app/                    # Code PHP (Controllers, Models)
├── bootstrap/              # Initialisation app
├── config/                 # Configuration
├── database/               # Migrations & Seeders
├── public/                 # Fichiers publics (images, assets)
├── resources/
│   ├── css/               # Tailwind CSS
│   ├── js/                # Alpine.js
│   └── views/             # Templates Blade ← Nouveau!
├── routes/                # Routes web & API
├── storage/               # Fichiers (uploads, logs)
├── tests/                 # Tests unitaires & fonctionnels
└── vendor/                # Dépendances PHP
```

---

## 🎨 Fonctionnalités Principales

### 1. **Authentification**
- Inscription (email, password)
- Connexion avec session
- Réinitialisation mot de passe
- Vérification email
- Confirmation password (actions sensibles)

### 2. **Publications & Réactions**
- Créer/éditer/supprimer publications
- Upload fichiers (images, vidéos)
- Réagir (like, love, etc.)
- Commenter
- Pagination dynamique

### 3. **Groupes**
- Créer groupes (public/privé)
- Rejoindre groupes
- Voir membres
- Publications de groupe
- Modération

### 4. **Messagerie**
- Conversations
- Messages privés
- Notifications
- Historique messages

### 5. **Profil Utilisateur**
- Éditer profil (nom, email)
- Changer mot de passe
- Supprimer compte
- Statistiques personnelles

### 6. **Admin Dashboard**
- Vue statistiques globales (users, publications, groupes)
- Gestion utilisateurs
- Modération contenu
- Signalements

---

## 📋 Routes Principales

| Route | Méthode | Auth | Fonction |
|-------|---------|------|----------|
| `/` | GET | ❌ | Page d'accueil |
| `/login` | GET/POST | ❌ | Authentification |
| `/register` | GET/POST | ❌ | Inscription |
| `/dashboard` | GET | ✅ | Tableau de bord |
| `/feed` | GET | ✅ | Fil d'actualités |
| `/publications/create` | GET/POST | ✅ | Créer publication |
| `/groupes` | GET | ✅ | Liste groupes |
| `/groupes/create` | GET/POST | ✅ | Créer groupe |
| `/groupes/{id}` | GET | ✅ | Détail groupe |
| `/messages` | GET | ✅ | Messagerie |
| `/profile` | GET/PATCH | ✅ | Éditer profil |
| `/admin` | GET | ✅ Admin | Admin panel |

---

## 🔌 API REST Endpoints

Tous les endpoints retournent du JSON avec Axios:

```
GET    /api/v1/publications      # Lister publications
POST   /api/v1/publications      # Créer publication
GET    /api/v1/publications/{id} # Détail publication
DELETE /api/v1/publications/{id} # Supprimer publication

GET    /api/v1/groupes           # Lister groupes
POST   /api/v1/groupes           # Créer groupe
GET    /api/v1/groupes/{id}      # Détail groupe
GET    /api/v1/groupes/{id}/publications  # Publications groupe

GET    /api/v1/messages          # Lister messages
POST   /api/v1/messages          # Envoyer message
GET    /api/v1/conversations     # Lister conversations

GET    /api/v1/reactions         # Lister réactions
POST   /api/v1/reactions         # Créer réaction

GET    /api/v1/admin/stats       # Stats admin
GET    /api/v1/admin/users       # Lister users (admin)
```

---

## 📦 Models Principaux

```php
User               // Utilisateur (auth)
Utilisateur        // Profil utilisateur (étendu)
Publication        // Publications publiques
Commentaire        // Commentaires publications
Reaction           // Réactions (like, love, etc.)
Groupe             // Groupes thématiques
Message            // Messages privés
Conversation       // Conversations (groupes de messages)
Media              // Fichiers uploadés
Notification       // Notifications utilisateur
Role               // Rôles utilisateurs (user, admin)
```

---

## 🔐 Sécurité

✅ **CSRF Protection** - Token automatique Axios
✅ **Password Hashing** - Bcrypt
✅ **Authentication** - Session-based
✅ **Authorization** - Middleware (auth, admin)
✅ **Input Validation** - Request classes
✅ **XSS Protection** - Blade escaping
✅ **SQL Injection** - Eloquent ORM
✅ **Rate Limiting** - Throttle middleware (optionnel)

---

## 🚀 Installation & Setup

### Prérequis
- PHP 8.2+
- Node.js 16+
- MySQL 5.7+ ou SQLite

### Installation Pas à Pas

```bash
# 1. Cloner le repo
git clone <repo-url>
cd Campus_Network

# 2. Installer dépendances PHP
composer install

# 3. Installer dépendances Node
npm install

# 4. Configurer l'app
cp .env.example .env
php artisan key:generate

# 5. Configurer la base de données
# Éditer .env avec vos infos DB
# puis exécuter:
php artisan migrate
php artisan db:seed  # Optionnel

# 6. Build des assets
npm run build

# 7. Démarrer le serveur
php artisan serve
```

### Pour le Développement
```bash
# Terminal 1 - Serveur Laravel
php artisan serve

# Terminal 2 - Watcher Vite (watch mode)
npm run dev
```

---

## 🧪 Tests

```bash
# Exécuter les tests
php artisan test

# Avec coverage
php artisan test --coverage

# Test spécifique
php artisan test tests/Feature/LoginTest.php
```

---

## 📚 Fichiers de Migration (Phase 1)

Cette version a été migrée de React à Blade PHP. Voir:

- **[RESUME_RAPIDE.md](RESUME_RAPIDE.md)** - Vue d'ensemble rapide
- **[MIGRATION_REACT_TO_BLADE.md](MIGRATION_REACT_TO_BLADE.md)** - Détails migration
- **[GUIDE_EXECUTION_COMPLET.md](GUIDE_EXECUTION_COMPLET.md)** - Instructions complètes
- **[CHECKLIST_COMPLETE.md](CHECKLIST_COMPLETE.md)** - Validation & tests
- **[COMPOSANTS_BLADE_BONUS.md](COMPOSANTS_BLADE_BONUS.md)** - Composants réutilisables

---

## 📁 Fichiers Clés

### Vues Blade
```
resources/views/
├── welcome.blade.php                 # Page d'accueil
├── dashboard.blade.php               # Dashboard utilisateur
├── feed.blade.php                    # Fil actualités
├── layouts/authenticated.blade.php   # Layout avec nav
├── auth/                             # Pages auth (6 fichiers)
├── publications/create.blade.php     # Créer publication
├── groupes/                          # Gestion groupes (3 fichiers)
├── messages/index.blade.php          # Messagerie
├── profile/edit.blade.php            # Profil utilisateur
└── admin/dashboard.blade.php         # Admin panel
```

### Configuration
```
vite.config.js       # Config Vite/Laravel plugin
tailwind.config.js   # Config Tailwind CSS
postcss.config.js    # Config PostCSS
jsconfig.json        # Alias @/*
package.json         # Dépendances npm
```

### Backend
```
app/Http/Controllers/      # Controllers
app/Models/                # Eloquent Models
app/Http/Requests/         # Form Requests
app/Http/Middleware/       # Middleware
routes/web.php             # Web routes
routes/api.php             # API routes
database/migrations/       # Migrations
```

---

## 💾 Base de Données

### Tables Principales

**Users**
```sql
id, name, email, password, email_verified_at, created_at, updated_at
```

**Publications**
```sql
id, user_id, titre, contenu, created_at, updated_at
```

**Groupes**
```sql
id, nom, description, visibilite (public/private), created_at
```

**Messages**
```sql
id, conversation_id, user_id, contenu, created_at
```

**Voir:** `database/migrations/` pour détails complets

---

## 🎨 Design System

**Tailwind CSS** avec personnalisations:
- Couleurs: Blue (primary), Gray (neutral), Red (danger), Green (success)
- Typography: Figtree font family
- Spacing: Standard Tailwind
- Components: Forms, Buttons, Cards, Alerts, Modals
- Responsive: Mobile-first

---

## ⚙️ Commandes Utiles

```bash
# Serveur
php artisan serve

# Migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh

# Seeders
php artisan db:seed
php artisan db:seed --class=UserSeeder

# Cache
php artisan cache:clear
php artisan view:clear

# Logs
tail -f storage/logs/laravel.log

# Routes
php artisan route:list

# Tinker (REPL)
php artisan tinker
```

---

## 📞 Support & Contribution

Pour des questions:
1. Consulter la documentation dans `docs/`
2. Vérifier les logs: `storage/logs/`
3. Consulter les guides de migration

Pour contribuer:
1. Fork le repo
2. Créer une branche feature
3. Commit vos changements
4. Push et créer un PR

---

## 📄 License

Campus Network est sous license MIT. Voir [LICENSE](LICENSE) pour détails.

---

## 👥 Équipe

- **Développement:** Campus Network Team
- **Migration React→Blade:** Complétée le 24/12/2025
- **Version:** 2.0

---

## 🎯 Roadmap

### Phase 1: ✅ Migration (Complétée)
- [x] Créer fichiers Blade
- [x] Configurer Alpine + Axios
- [x] Générer documentation

### Phase 2: ⏳ Installation & Tests
- [ ] npm install
- [ ] Supprimer React code
- [ ] Tests locaux

### Phase 3: ⏳ Optimisations
- [ ] Composants réutilisables
- [ ] Performance tuning
- [ ] SEO

### Phase 4: ⏳ Déploiement
- [ ] Build production
- [ ] Migration données
- [ ] Go live

---

## 📊 Statistiques Projet

| Métrique | Valeur |
|----------|--------|
| Fichiers Blade | 20 |
| Routes | 20+ |
| Models | 10 |
| API Endpoints | 15+ |
| Utilisateurs ciblés | Communauté académique |
| Langues supportées | Français |

---

## 🎉 Version Actuelle

**Campus Network v2.0**
- Blade PHP Edition
- 100% Blade (zéro React)
- Alpine.js pour interactivité
- Tailwind CSS pour design
- Prête pour production

---

**Généré:** 24 Décembre 2025
**Statut:** Phase 1 Complète ✅

Pour continuer: Voir [RESUME_RAPIDE.md](RESUME_RAPIDE.md)

