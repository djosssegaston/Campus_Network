# 🚀 GUIDE D'INSTALLATION DES NOUVELLES FONCTIONNALITÉS

**Date**: 26 Décembre 2025
**Version**: Campus Network v1.1

---

## ✅ Prérequis

- Laravel 12.43.1
- PHP 8.2.4+
- SQLite ou PostgreSQL
- Composer

---

## 📦 Installation Complète

### 1️⃣ Exécuter les migrations

```bash
# Exécuter toutes les migrations en attente
php artisan migrate

# Ou pas à pas
php artisan migrate --step
```

**Migrations créées**:
- `create_user_privacy_settings_table` - Configuration de confidentialité
- `create_data_exports_table` - Gestion des exports RGPD

### 2️⃣ Initialiser les données

```bash
# Créer les paramètres de confidentialité pour les utilisateurs existants
php artisan db:seed --class=UserPrivacySettingsSeeder
```

### 3️⃣ Vider les caches

```bash
# Vider le cache des vues
php artisan view:clear

# Vider le cache des routes
php artisan route:clear

# Vider tous les caches
php artisan cache:clear
```

### 4️⃣ Vérification

```bash
# Vérifier que les routes sont disponibles
php artisan route:list | grep -E "(search|privacy|exports)"

# Vérifier les migrations
php artisan migrate:status
```

---

## 📁 Fichiers Créés

### Contrôleurs (6 fichiers)

```
✅ app/Http/Controllers/SearchController.php
✅ app/Http/Controllers/PrivacySettingController.php
✅ app/Http/Controllers/ExportController.php
✅ app/Http/Controllers/Api/SearchController.php
✅ app/Http/Controllers/Api/PrivacySettingController.php
✅ app/Http/Controllers/Api/ExportController.php
```

### Modèles (2 fichiers)

```
✅ app/Models/UserPrivacySetting.php
✅ app/Models/DataExport.php
```

### Jobs (1 fichier)

```
✅ app/Jobs/ExportUserDataJob.php
```

### Vues (3 fichiers)

```
✅ resources/views/search/index.blade.php
✅ resources/views/profile/privacy-settings.blade.php
✅ resources/views/profile/exports.blade.php
```

### Migrations (2 fichiers)

```
✅ database/migrations/0001_01_01_000031_create_user_privacy_settings_table.php
✅ database/migrations/0001_01_01_000032_create_data_exports_table.php
```

### Seeders (1 fichier)

```
✅ database/seeders/UserPrivacySettingsSeeder.php
```

### Documentation (3 fichiers)

```
✅ AUDIT_COMPLET_FONCTIONNALITES.md
✅ RÉSUMÉ_D_IMPLÉMENTATION.md
✅ GUIDE_UTILISATEUR_NOUVELLES_FONCTIONNALITES.md
```

---

## 🔧 Configuration Requise

### 1️⃣ Variables d'Environnement

Si vous utilisez queues asynchrones pour les exports, ajouter à `.env`:

```env
# Pour les jobs en arrière-plan
QUEUE_CONNECTION=sync  # Ou 'database', 'redis', etc.

# Stockage des exports
FILESYSTEM_DISK=local
```

### 2️⃣ Dossiers de Stockage

Les fichiers d'export sont stockés dans:

```
storage/app/exports/
├── json/
├── csv/
└── zip/
```

✅ Ces dossiers sont créés automatiquement.

### 3️⃣ Routes

Les routes sont automatiquement enregistrées dans:
- `routes/web.php` - Routes web (SSR Blade)
- `routes/api.php` - Routes API (JSON)

Pas besoin de configuration supplémentaire.

---

## 🧪 Tests

### Test 1: Recherche

```bash
# Via le navigateur
http://localhost:8000/search?q=test

# Via API
curl "http://localhost:8000/api/v1/search?q=test"
```

### Test 2: Confidentialité

```bash
# Accéder à la page
http://localhost:8000/profile/privacy

# Tester l'API
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/v1/privacy-settings
```

### Test 3: Export RGPD

```bash
# Accéder à la page
http://localhost:8000/profile/exports

# Tester l'API
curl -X POST \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"format":"json"}' \
  http://localhost:8000/api/v1/exports
```

---

## ⚠️ Points d'Attention

### 1️⃣ Permissions

- Les contrôleurs API utilisent le middleware `auth:sanctum`
- Les contrôleurs web utilisent le middleware `auth`
- Les permissions doivent être vérifiées avant accès

### 2️⃣ Queues

Pour les exports asynchrones:

```bash
# Démarrer le worker queue
php artisan queue:work

# Ou via Supervisor
# Voir config/supervisor.conf
```

### 3️⃣ Base de Données

Les migrations utilisent:
- `utilisateurs` comme table d'utilisateurs (pas `users`)
- Clés étrangères avec cascades de suppression
- Indices sur colonnes fréquemment recherchées

### 4️⃣ Stockage

Les exports sont stockés localement dans `storage/app/`:
- ✅ Accessible via download
- ✅ Nettoyage manuel recommandé
- ✅ À ajouter au `.gitignore` si nécessaire

---

## 🔄 Mise à Jour Future

Si vous devez mettre à jour ces fonctionnalités:

1. **Modifiez les contrôleurs** dans `app/Http/Controllers/`
2. **Ajoutez des migrations** pour les changements DB
3. **Exécutez** `php artisan migrate`
4. **Videz le cache** avec `php artisan cache:clear`

---

## 📊 Architecture

### Pattern d'Architecture Respectée

```
Web Routes (Blade SSR)
    ↓
PublicViewController → View (HTML)
    ↓
└── Utilisateur → BelongsTo Role

API Routes (JSON)
    ↓
ApiController (Sanctum) → JsonResponse
    ↓
└── Utilisateur → BelongsTo Role
```

### Base de Données

```
utilisateurs
├── id (PK)
├── nom, email
├── role_id (FK)
└── ...

user_privacy_settings
├── id (PK)
├── utilisateur_id (FK, unique)
└── ... (13 paramètres de confidentialité)

data_exports
├── id (PK)
├── utilisateur_id (FK)
├── status (pending/processing/completed/failed)
├── file_path, file_name
└── ... (métadonnées d'export)
```

---

## 📞 Dépannage

### Erreur: Route not found

```
Solution: Exécutez php artisan route:clear
```

### Erreur: Table not found

```
Solution: Exécutez php artisan migrate
```

### Erreur: File not found (Export)

```
Solution: Vérifiez que storage/app/exports/ existe et est accessible
```

### Erreur: Job failed (Export)

```
Solution: 
1. Vérifiez QUEUE_CONNECTION dans .env
2. Vérifiez les logs: storage/logs/laravel.log
3. Testez le Job: php artisan queue:work --timeout=0
```

---

## ✨ Fonctionnalités Activées

Après l'installation, vous avez accès à:

| Fonctionnalité | Web | API | Status |
|---|---|---|---|
| Recherche Globale | ✅ | ✅ | ✅ Complet |
| Confidentialité | ✅ | ✅ | ✅ Complet |
| Export RGPD | ✅ | ✅ | ✅ Complet |

---

## 📚 Documentation Supplémentaire

- [GUIDE_UTILISATEUR_NOUVELLES_FONCTIONNALITES.md](GUIDE_UTILISATEUR_NOUVELLES_FONCTIONNALITES.md) - Guide utilisateur
- [AUDIT_COMPLET_FONCTIONNALITES.md](AUDIT_COMPLET_FONCTIONNALITES.md) - Audit du projet
- [RÉSUMÉ_D_IMPLÉMENTATION.md](RÉSUMÉ_D_IMPLÉMENTATION.md) - Détails techniques

---

## 🎉 Installation Complète!

Vous êtes prêt à utiliser les nouvelles fonctionnalités. 

👉 **Commencez par** : `/search` ou `/profile/privacy` ou `/profile/exports`

---

**Support**: Consultez les logs `storage/logs/laravel.log` en cas de problème
