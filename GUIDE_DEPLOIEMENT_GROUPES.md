# 🚀 DÉPLOIEMENT - Groupes Améliorés

## ✅ Pré-Déploiement

Avant de mettre en production, vérifiez :

```bash
# 1. Tester la syntaxe PHP
php artisan tinker
echo "OK"

# 2. Vérifier les migrations
php artisan migrate:status

# 3. Vérifier les routes
php artisan route:list | grep groupe

# 4. Vérifier le cache
php artisan optimize

# 5. Vérifier les permissions
ls -la storage/    # doit être 755

# 6. Vérifier .env
grep FILESYSTEM_DISK .env   # doit être "public"
```

---

## 📦 Fichiers à Déployer

### Code PHP
```
✅ app/Http/Controllers/GroupeMessageController.php
✅ app/Http/Controllers/GroupePublicationController.php
✅ app/Http/Controllers/GroupeSettingController.php
✅ app/Models/GroupeMessage.php
✅ app/Models/GroupeSetting.php
✅ app/Models/Groupe.php (modifié)
✅ routes/web.php (modifié)
```

### Vues
```
✅ resources/views/groupes/settings.blade.php (nouveau)
✅ resources/views/groupes/show.blade.php (modifié)
```

### Migrations
```
✅ database/migrations/2025_12_27_000001_create_groupe_messages_table.php
✅ database/migrations/2025_12_27_000002_create_groupe_settings_table.php
```

### Documentation
```
✅ DEMARRAGE_RAPIDE_GROUPES.md
✅ GUIDE_GROUPES_UTILISATEUR.md
✅ IMPLEMENTATION_GROUPES_COMPLET.md
✅ ROUTES_ET_POINTS_ENTREE.md
✅ RESULTAT_FINAL_GROUPES.md
✅ INDEX_GROUPES_DOCUMENTATION.md
✅ SYNTHESE_IMPLEMENTATION_GROUPES.md
✅ GUIDE_TEST_GROUPES.md
✅ DEMARRAGE_DEPLOIEMENT.md (ce fichier)
```

---

## 🔄 Processus de Déploiement

### Étape 1 : Préparer le Serveur

```bash
# Se connecter au serveur
ssh user@your-server.com

# Aller dans le répertoire du projet
cd /var/www/votre-app

# Vérifier la branche main
git status
```

### Étape 2 : Télécharger le Code

```bash
# Récupérer les derniers changements
git pull origin main

# Ou manuellement copier les fichiers:
# - Controllers
# - Models
# - Views
# - Migrations
```

### Étape 3 : Exécuter les Migrations

```bash
# Important !!! Cette étape crée les tables
php artisan migrate

# Vérifier que les migrations sont OK
php artisan migrate:status
```

### Étape 4 : Mettre en Cache

```bash
# Vider le cache
php artisan optimize:clear

# Créer le cache
php artisan optimize

# Vérifier le cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Étape 5 : Vérifier les Permissions

```bash
# Storage doit être accessible
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Public doit être accessible
chmod -R 755 public/storage

# Lien symbolique (si nécessaire)
php artisan storage:link
```

### Étape 6 : Test Final

```bash
# Vérifier l'application
php artisan tinker
Groupe::count()   # doit retourner un nombre

# Exit tinker
exit

# Visiter l'application
curl http://votre-app/groupes
```

---

## 🌐 Configuration Serveur

### .env Requis

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=votre_db
DB_USERNAME=votre_user
DB_PASSWORD=votre_pass

# Storage
FILESYSTEM_DISK=public

# Queue (optionnel)
QUEUE_CONNECTION=database

# Mail (optionnel)
MAIL_DRIVER=smtp
```

### nginx.conf

```nginx
location /storage {
    alias /var/www/votre-app/storage/app/public;
}

# Ou

location ~ /storage/groupes/ {
    alias /var/www/votre-app/storage/app/public/groupes/;
}
```

### Apache .htaccess

```
<Directory /var/www/votre-app/storage/app/public>
    Allow from all
</Directory>
```

---

## 📊 Vérifications Post-Déploiement

### Check Base de Données

```sql
-- Vérifier que les tables existent
SHOW TABLES LIKE 'groupe_%';

-- Vérifier la structure
DESCRIBE groupe_messages;
DESCRIBE groupe_settings;

-- Vérifier les données
SELECT COUNT(*) FROM groupe_messages;
SELECT COUNT(*) FROM groupe_settings;
```

### Check Fichiers

```bash
# Vérifier les permissions
ls -la storage/app/public/groupes/
# Doit montrer les fichiers uploadés

# Test upload
touch storage/app/public/test.txt
ls storage/app/public/test.txt
rm storage/app/public/test.txt
```

### Check Routes

```bash
# Vérifier les routes
php artisan route:list | grep groupe-

# Doit afficher:
# POST   groupes/{groupe}/messages
# POST   groupes/{groupe}/publications
# ...
```

---

## 🔒 Sécurité Post-Déploiement

### Vérifications

```bash
# 1. APP_DEBUG doit être false en production
grep APP_DEBUG .env    # false

# 2. APP_KEY doit être généré
grep APP_KEY .env      # ne doit pas être vide

# 3. Permissions
ls -la app/       # 755
ls -la config/    # 755
ls -la storage/   # 775
ls -la bootstrap/ # 775
```

### Headers de Sécurité

Ajouter dans votre vhost :

```
# Middleware Laravel prend en charge
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
```

---

## 🚨 Rollback d'Urgence

Si quelque chose se passe mal :

```bash
# Récupérer la version précédente
git revert HEAD

# Ou
git checkout HEAD~1

# Revert les migrations
php artisan migrate:rollback --step=2

# Nettoyer le cache
php artisan optimize:clear
```

---

## 📈 Monitoring Post-Déploiement

### Logs

```bash
# Voir les erreurs
tail -f storage/logs/laravel.log

# Filtrer les erreurs
grep -i error storage/logs/laravel.log

# Nombre d'erreurs
grep -i error storage/logs/laravel.log | wc -l
```

### Performance

```bash
# Vérifier la requête DB
php artisan tinker
DB::enableQueryLog()
Groupe::with('messages')->paginate()
print_r(DB::getQueryLog())
```

### Storage

```bash
# Taille des uploads
du -sh storage/app/public/groupes/

# Fichiers
find storage/app/public/groupes/ -type f | wc -l
```

---

## 🎯 Déploiement Avec CI/CD

### GitHub Actions

```yaml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Deploy
        run: |
          ssh user@server "cd /var/www/app && git pull && php artisan migrate"
```

### GitLab CI

```yaml
deploy:
  stage: deploy
  script:
    - ssh user@server "cd /var/www/app"
    - git pull origin main
    - php artisan migrate
    - php artisan optimize
  only:
    - main
```

---

## 📋 Checklist de Déploiement

- [ ] Code en git (push main)
- [ ] Migrations prêtes
- [ ] .env configuré
- [ ] Storage permissions OK
- [ ] Database sauvegardée
- [ ] Backup de production
- [ ] Pull le code
- [ ] Exécuter les migrations
- [ ] Vider cache
- [ ] Vérifier logs
- [ ] Test les routes
- [ ] Test les uploads
- [ ] Notification l'équipe

---

## 🆘 Dépannage Post-Déploiement

### "Migrations not found"
```bash
# Vérifier que les fichiers existent
ls database/migrations/2025_12_27_*

# Ou copier les fichiers manquants
cp local-app/database/migrations/2025_* production-app/database/migrations/
```

### "Permission denied on storage"
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
php artisan storage:link
```

### "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### "Routes not available"
```bash
php artisan route:clear
php artisan route:cache
```

### "Fichiers ne s'affichent pas"
```bash
# Vérifier le lien
ls -la public/storage

# Créer s'il manque
php artisan storage:link

# Vérifier .env
grep FILESYSTEM_DISK .env    # doit être "public"
```

---

## 📞 Support Déploiement

**Besoin d'aide ?**

1. Consulter les logs : `storage/logs/laravel.log`
2. Vérifier les permissions : `ls -la storage/`
3. Vérifier la DB : `php artisan tinker` → `Groupe::count()`
4. Vérifier les routes : `php artisan route:list`
5. Vérifier .env : `cat .env | grep -i app`

---

## ✅ Post-Déploiement

Après le déploiement, :

- [ ] Tester une publication
- [ ] Tester un upload d'image
- [ ] Tester les paramètres (admin)
- [ ] Vérifier les logs
- [ ] Notifier les utilisateurs
- [ ] Surveiller la performance

---

## 📊 Monitoring Continu

```bash
# Daily
watch -n 60 'tail -20 storage/logs/laravel.log'

# Weekly
du -sh storage/app/public/groupes/

# Monthly
mysql votre_db -e "SELECT COUNT(*) FROM groupe_messages;"
```

---

**Déploiement Version** : 1.0  
**Date** : 27 Décembre 2025  
**Status** : Production Ready ✅
