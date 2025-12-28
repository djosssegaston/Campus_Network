# 🚀 COMMANDES RAPIDES D'AUDIT

**Vérify Quickly Campus Network State**

---

## 📋 VÉRIFIER L'ÉTAT DU PROJET

### 1. État de la BD (Migrations)
```bash
cd c:\Users\HP\Desktop\Campus_Network

# Voir toutes les migrations
php artisan migrate:status

# Voir les modèles
php artisan tinker
>>> \App\Models\Utilisateur::count()  # Doit retourner un nombre
>>> \App\Models\Publication::count()
>>> \App\Models\Groupe::count()
>>> exit
```

### 2. Vérifier les Routes
```bash
# Voir toutes les routes implémentées
php artisan route:list

# Filtrer par admin
php artisan route:list --only-options=admin

# Filtrer par resource
php artisan route:list | grep publication
php artisan route:list | grep groupe
php artisan route:list | grep message
```

### 3. Vérifier les Modèles & Relations
```bash
php artisan tinker

# Utilisateur
$user = \App\Models\Utilisateur::first();
$user->publications->count()  # Publications de l'user
$user->role->nom              # Rôle de l'user
$user->estAdmin()             # Est-il admin?

# Publication
$pub = \App\Models\Publication::first();
$pub->utilisateur->nom        # Qui a écrit?
$pub->commentaires->count()   # Combien de comments?
$pub->groupe?->nom            # Dans quel groupe?

# Groupe
$groupe = \App\Models\Groupe::first();
$groupe->utilisateurs->count()  # Combien de members?
$groupe->publications->count()  # Combien de posts?
$groupe->hasMember($user)       # User est member?

exit
```

---

## 🧪 TESTS RAPIDES

### 1. Tester Authentification
```bash
# Login test
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password"}'

# Devrait retourner: Redirect vers /dashboard ou erreur login
```

### 2. Tester API (Si Sanctum configuré)
```bash
# Get token
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password"}'

# Récupère les publications
curl -X GET http://localhost:8000/api/v1/publications \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 3. Tester Routes Admin
```bash
# Non-authentifié (devrait rediriger)
curl http://localhost:8000/admin

# Authentifié mais pas admin (devrait 403)
# Authentifié et admin (devrait afficher dashboard)
```

### 4. Tester Upload Media
```bash
# Tester upload (devrait valider mime type)
curl -X POST http://localhost:8000/publications \
  -F "media=@test.jpg" \
  -F "contenu=Test publication"

# Essayer upload malveillant (devrait rejeter)
curl -X POST http://localhost:8000/publications \
  -F "media=@malware.exe"
```

---

## 🔍 VÉRIFICATIONS DE SÉCURITÉ

### 1. CSRF Protection
```bash
# Doit avoir token CSRF
curl http://localhost:8000/publications/create | grep csrf_token

# POST sans token (devrait 419)
curl -X POST http://localhost:8000/publications \
  -d "contenu=Test"
```

### 2. XSS Prevention
```bash
# Test dans un commentaire
curl -X POST http://localhost:8000/publications/1/commentaires \
  -d "contenu=<script>alert('xss')</script>"

# La réponse devrait échapper le script (pas d'exécution)
```

### 3. SQL Injection
```bash
# Test dans recherche
curl "http://localhost:8000/search?q='; DROP TABLE users; --"

# Devrait être inoffensif (prepared statements)
```

### 4. Rate Limiting (Si implémenté)
```bash
# Faire 100 requêtes rapidement
for i in {1..100}; do
  curl -X POST http://localhost:8000/publications \
    -d "contenu=Spam"
done

# Devrait 429 Too Many Requests à un point
```

---

## 📊 VÉRIFIER LES PERFORMANCES

### 1. Query Count (Éviter N+1)
```bash
php artisan tinker

# Avec eager loading (BON)
>>> $publications = \App\Models\Publication::with('utilisateur')->get();
>>> echo DB::getQueryLog(); // Devrait être ~2 queries

# Sans eager loading (MAUVAIS)
>>> $publications = \App\Models\Publication::get();
>>> foreach($publications as $pub) { $pub->utilisateur->nom; } 
>>> echo DB::getQueryLog(); // Devrait être 1 + n queries

exit
```

### 2. Query Time
```bash
# Voir logs MySQL
tail -f /var/log/mysql/mysql.log | grep Query_time

# Utiliser Laravel Debugbar
composer require barryvdh/laravel-debugbar --dev

# Visiter une page, see "Queries" tab
# Chaque query devrait être < 100ms
```

### 3. Memory Usage
```bash
# Tester avec beaucoup de données
php artisan tinker

>>> $publications = \App\Models\Publication::all(); // 10k rows
>>> memory_get_usage() / 1024 / 1024 . ' MB'

# Devrait être < 256MB même avec 10k rows si lazy loaded
```

---

## 🧩 VÉRIFIER LES COMPONANTS CLÉ

### 1. Middleware
```bash
# Vérifier IsAdmin middleware
php artisan tinker
>>> $user = \App\Models\Utilisateur::first();
>>> $admin_user = \App\Models\Utilisateur::where('email','admin@test.com')->first();
>>> echo $admin_user->estAdmin() ? 'ADMIN' : 'NOT ADMIN';
exit

# Vérifier CheckBannedUser middleware
>>> $banned_user = \App\Models\Utilisateur::where('ban_until', '>', now())->first();
>>> echo $banned_user ? 'BANNED' : 'NOT BANNED';
exit
```

### 2. Validation (Form Requests)
```bash
# Test validation error
curl -X POST http://localhost:8000/publications \
  -H "Content-Type: application/json" \
  -d '{"contenu":""}' # contenu obligatoire

# Devrait retourner 422 avec messages d'erreur
```

### 3. Eloquent Relationships
```bash
php artisan tinker

# Publications -> Utilisateur
>>> \App\Models\Publication::with('utilisateur')->first()->utilisateur->nom

# Groupe -> Publications
>>> \App\Models\Groupe::with('publications')->first()->publications->count()

# Message -> Conversation -> Utilisateurs
>>> \App\Models\Message::with('conversation.utilisateurs')->first()->conversation->utilisateurs->count()

exit
```

---

## 📝 VÉRIFIER LES FICHIERS CRITIQUES

### 1. Modèles Principaux
```bash
# Utilisateur
ls -la app/Models/Utilisateur.php  # Doit exister

# Tous les modèles
ls -la app/Models/ | wc -l  # Devrait être 15+

# Contrôleurs
ls -la app/Http/Controllers/ | wc -l  # Devrait être 20+
```

### 2. Migrations
```bash
# Lister les migrations
ls -la database/migrations/ | grep -c ".php"  # Devrait être 18+

# Soft deletes
grep -r "SoftDeletes" app/Models/ | wc -l  # Devrait être 6+
```

### 3. Routes
```bash
# Compter les routes
php artisan route:list | wc -l  # Devrait être 50+

# Routes admin
php artisan route:list | grep "/admin" | wc -l  # Devrait être 25+
```

---

## 🔧 COMMANDES DE MAINTENANCE

### 1. Nettoyer le Projet
```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Composer autoload
composer dump-autoload

# NPM build (si assets)
npm run build
```

### 2. Vérifier la Santé de l'App
```bash
# Health check
php artisan schedule:list

# Vérifier permissions fichiers
chmod -R 755 storage bootstrap/cache

# Vérifier variables d'env
php artisan env
```

### 3. Réinitialiser pour Tests
```bash
# Reset BD complètement
php artisan migrate:fresh --seed

# Reset cache
php artisan cache:clear

# Reset queue
php artisan queue:clear
```

---

## 📊 EXPORTER RAPIDEMENT MÉTRIQUES

### 1. Utilisateurs
```bash
php artisan tinker
>>> \App\Models\Utilisateur::count()  # Total users
>>> \App\Models\Utilisateur::where('email_verified_at', '!=', null)->count()  # Verified
>>> \App\Models\Utilisateur::where('ban_until', '>', now())->count()  # Banned
>>> \App\Models\Role::all()  # Tous les rôles
exit
```

### 2. Contenu
```bash
php artisan tinker
>>> \App\Models\Publication::count()  # Total posts
>>> \App\Models\Groupe::count()  # Total groups
>>> \App\Models\Message::count()  # Total messages
>>> \App\Models\Commentaire::count()  # Total comments
exit
```

### 3. Engagement
```bash
php artisan tinker
>>> \App\Models\Reaction::count()  # Total likes
>>> \App\Models\Partage::count()  # Total shares
>>> \App\Models\Publication::where('created_at', '>=', now()->subDay())->count()  # Posts 24h
exit
```

---

## 🧪 FULL SYSTEM CHECK (À EXÉCUTER CHAQUE SEMAINE)

### Créer ce script: `check_system.sh`

```bash
#!/bin/bash

echo "=== CAMPUS NETWORK SYSTEM CHECK ==="
echo ""

# 1. BD
echo "1. DATABASE"
php artisan migrate:status | head -5
echo ""

# 2. Models
echo "2. MODELS"
php artisan tinker --quiet <<'EOL'
$models = count(glob(__DIR__ . '/app/Models/*.php'));
echo "Models: $models\n";
EOL
echo ""

# 3. Routes
echo "3. ROUTES"
php artisan route:list | head -5
echo ""

# 4. Cache
echo "4. CACHE"
du -sh storage/framework/cache/
echo ""

# 5. Logs
echo "5. LOGS"
tail -1 storage/logs/laravel.log
echo ""

echo "=== CHECK COMPLETE ==="
```

### Exécuter:
```bash
bash check_system.sh
```

---

## 🚀 DÉMARRAGE RAPIDE POUR TESTS

```bash
# 1. Naviguer vers le projet
cd c:\Users\HP\Desktop\Campus_Network

# 2. Démarrer serveur
php artisan serve

# 3. Dans un autre terminal, test
php artisan tinker

# 4. Vérifier une route
curl http://localhost:8000/dashboard

# 5. Voir les logs
tail -f storage/logs/laravel.log
```

---

## 📌 PRÉ-CHECKLIST AVANT PRODUCTION

```bash
# Copier et exécuter:

echo "✓ Migrations"
php artisan migrate:status | grep -c "✓" && echo "OK" || echo "FAIL"

echo "✓ Models (11+)"
ls app/Models/*.php | wc -l

echo "✓ Controllers (20+)"  
ls app/Http/Controllers/*.php | wc -l

echo "✓ Routes (50+)"
php artisan route:list | wc -l

echo "✓ Cache cleared"
php artisan cache:clear && echo "OK" || echo "FAIL"

echo "✓ Config cached"
php artisan config:cache && echo "OK" || echo "FAIL"

echo "✓ Database connected"
php artisan tinker --quiet <<'EOL'
try {
    DB::connection()->getPdo();
    echo "OK\n";
} catch (Exception $e) {
    echo "FAIL\n";
}
EOL
```

---

**Dernière révision**: Décembre 2025
**Pour plus d'infos**: Voir AUDIT_FONCTIONNALITES_COMPLETE_2025.md
