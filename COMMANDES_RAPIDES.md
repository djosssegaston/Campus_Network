# ⚡ **COMMANDES RAPIDES - CAMPUS NETWORK**

## 🚀 **DÉMARRAGE DU PROJET**

```bash
# Terminal 1 - Serveur Laravel
cd c:\Users\HP\Campus_Network
php artisan serve --port=8000

# Terminal 2 - Compiler les assets (optionnel)
npm run dev
```

**Accès:** `http://localhost:8000`

---

## 🧹 **MAINTENANCE & NETTOYAGE**

```bash
# Vider TOUS les caches
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear

# Réinitialiser la base de données
php artisan migrate:fresh --seed

# Vérifier la syntaxe PHP
php artisan check

# Vérifier l'état des migrations
php artisan migrate:status
```

---

## 🗂️ **GESTION DE LA BASE DE DONNÉES**

```bash
# Appliquer les migrations
php artisan migrate

# Annuler les migrations
php artisan migrate:rollback

# Réinitialiser complètement
php artisan migrate:fresh

# Ajouter des données de test
php artisan db:seed
```

---

## 🏗️ **GÉNÉRER DE NOUVEAUX COMPOSANTS**

```bash
# Créer un Contrôleur
php artisan make:controller NomControleur

# Créer un Modèle
php artisan make:model NomModele

# Créer Modèle + Migration
php artisan make:model NomModele -m

# Créer une Migration
php artisan make:migration create_nouvelle_table

# Créer une Politique (Authorization)
php artisan make:policy NomPolicy

# Créer un Event
php artisan make:event NomEvent

# Créer un Listener
php artisan make:listener NomListener
```

---

## 🧪 **TESTER LE PROJET**

```bash
# Lancer tous les tests
php artisan test

# Tester un fichier spécifique
php artisan test --filter=PublicationTest

# Tests avec couverture de code
php artisan test --coverage

# Créer un test
php artisan make:test PublicationTest
```

---

## 📊 **VOIR LES ROUTES**

```bash
# Lister toutes les routes
php artisan route:list

# Filtrer par nom
php artisan route:list --name=publications

# Format JSON
php artisan route:list --json
```

---

## 🔍 **DEBUG & LOGS**

```bash
# Voir les 50 derniers logs
Get-Content storage/logs/laravel.log -Tail 50

# Suivre les logs en temps réel (Linux/Mac)
tail -f storage/logs/laravel.log

# Vider le fichier de log
Clear-Content storage/logs/laravel.log  # Windows
rm storage/logs/laravel.log  # Linux/Mac
```

---

## 🛠️ **TINKER - Vérifier les Données**

```bash
# Ouvrir Tinker (interactif)
php artisan tinker

# Exemples (à exécuter dans Tinker)
User::count()                                    # Compter les utilisateurs
User::first()                                   # Premier utilisateur
Utilisateur::where('email', 'email@test.com')->first()  # Trouver par email
Publication::latest()->first()                  # Dernière publication
Groupe::with('utilisateurs')->get()            # Groupes avec membres
```

---

## 🔐 **GESTION DES UTILISATEURS**

```bash
# Créer un utilisateur (via Tinker)
php artisan tinker
Utilisateur::create([
    'nom' => 'Jean Dupont',
    'email' => 'jean@example.com',
    'password' => Hash::make('password123'),
    'role_id' => 1
])
exit
```

---

## 📦 **GESTION DES PACKAGES**

```bash
# Installer les dépendances PHP
composer install

# Mettre à jour les packages
composer update

# Installer les dépendances Node
npm install

# Compiler les assets
npm run build

# Mode développement avec watch
npm run dev
```

---

## 🚀 **DÉPLOIEMENT RAPIDE**

### **Heroku (pour tester)**
```bash
heroku login
heroku create mon-campus-network
git push heroku main
heroku run "php artisan migrate"
heroku open
```

### **VPS Standard (Recommandé)**
```bash
# Sur le serveur
git clone https://github.com/votre-repo/campus-network.git
cd campus-network
composer install --no-dev
php artisan migrate
npm run build
```

---

## 🔄 **GIT - VERSIONNER VOTRE CODE**

```bash
# Initialiser le repo
git init
git add .
git commit -m "Initial commit - Campus Network"

# Ajouter une remote
git remote add origin https://github.com/votre-user/campus-network.git

# Pousser le code
git push -u origin main

# Créer une branche pour une feature
git checkout -b feature/nouvelle-fonctionnalite
# ... faire des modifications ...
git add .
git commit -m "Ajouter nouvelle fonctionnalité"
git push origin feature/nouvelle-fonctionnalite

# Merger dans main
git checkout main
git pull
git merge feature/nouvelle-fonctionnalite
git push origin main
```

---

## ⚙️ **CONFIGURATION**

### **Changer la langue (en français)**
Modifiez `.env`:
```
APP_LOCALE=fr
```

Et dans `config/app.php`:
```php
'locale' => 'fr',
'fallback_locale' => 'fr',
```

### **Changer le fuseau horaire**
Dans `config/app.php`:
```php
'timezone' => 'Europe/Paris',
```

### **Configurer l'email**
Dans `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
MAIL_FROM_ADDRESS=noreply@campus-network.com
MAIL_FROM_NAME="Campus Network"
```

---

## 💡 **TRICKS UTILES**

```bash
# Générer une nouvelle APP_KEY
php artisan key:generate

# Créer un lien symbolique pour storage
php artisan storage:link

# Optimiser l'autoload
composer dump-autoload

# Effacer tous les fichiers de session
php artisan session:cache
php artisan session:clear

# Réchauffer les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Effacer les caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Voir la version de Laravel
php artisan --version

# Voir la version de PHP
php --version

# Lancer une commande spécifique
php artisan schedule:run

# Tester une route spécifique
curl -X GET http://localhost:8000/api/test
```

---

## 🎯 **WORKFLOW RECOMMANDÉ**

### **Avant de commencer**
```bash
cd c:\Users\HP\Campus_Network
git pull                           # Récupérer les derniers changements
composer install                   # Mettre à jour les packages
php artisan migrate                # Appliquer les migrations
npm install && npm run build       # Compiler les assets
```

### **Pendant le développement**
```bash
# Terminal 1
php artisan serve --port=8000

# Terminal 2
npm run dev  # Watch mode
```

### **Avant de commiter**
```bash
php artisan test                   # Vérifier les tests
php artisan check                  # Vérifier la syntaxe
git add .
git commit -m "Description du changement"
git push
```

---

## 📋 **CHECKLIST QUOTIDIENNE**

- [ ] Serveur lancé sur le bon port (8000)
- [ ] Assets compilés (`npm run build`)
- [ ] Base de données à jour (`php artisan migrate`)
- [ ] Pas d'erreurs dans les logs
- [ ] Tests qui passent (`php artisan test`)

---

## 🆘 **AIDE RAPIDE**

| Problème | Solution |
|----------|----------|
| Port 8000 utilisé | `php artisan serve --port=8001` |
| Erreur migration | `php artisan migrate:fresh --seed` |
| Pas de CSS/JS | `npm run build` |
| Class not found | `composer dump-autoload` |
| Erreur cache | `php artisan cache:clear` |
| Base corrompue | `php artisan migrate:refresh` |

---

## 📞 **BESOIN D'AIDE?**

```bash
# Voir l'aide d'une commande
php artisan help make:model

# Voir toutes les commandes disponibles
php artisan list
```

---

**Bonne chance! 🚀**

Gardez ces commandes à portée de main pour développer efficacement!
