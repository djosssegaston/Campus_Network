# 🧪 GUIDE DE TEST - Campus Network

## ✅ Tests à Effectuer

### 1. **Vérification des Migrations**
```bash
# Vérifier que les tables utilisent soft deletes
php artisan tinker

# Dans tinker:
>>> \App\Models\Publication::withTrashed()->count()
>>> \App\Models\Commentaire::onlyTrashed()->first()
```

### 2. **Tests Contrôleurs API**

#### Authentification
```bash
# Créer un utilisateur de test
POST /register
{
    "nom": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}

# Login
POST /login
{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Publications
```bash
# Créer une publication
POST /api/v1/publications
Authorization: Bearer {token}
Content-Type: application/json

{
    "titre": "Ma première publication",
    "contenu": "Ceci est le contenu de ma publication",
    "visibilite": "publique"
}
```

#### Commentaires
```bash
# Ajouter un commentaire
POST /api/v1/publications/1/commentaires
Authorization: Bearer {token}

{
    "contenu": "Excellent post!"
}
```

#### Réactions
```bash
# Ajouter une réaction
POST /api/v1/publications/1/reactions
Authorization: Bearer {token}

{
    "type": "like"
}
```

#### Groupes
```bash
# Créer un groupe
POST /api/v1/groupes
Authorization: Bearer {token}

{
    "nom": "Groupe d'étude",
    "description": "Un groupe pour étudier ensemble",
    "visibilite": "public"
}

# Rejoindre un groupe
POST /api/v1/groupes/1/join
Authorization: Bearer {token}

# Obtenir les publications du groupe
GET /api/v1/groupes/1/publications
```

#### Messages
```bash
# Créer une conversation
POST /api/v1/conversations
Authorization: Bearer {token}

{
    "titre": "Conversation privée",
    "utilisateur_ids": [2, 3]
}

# Envoyer un message
POST /api/v1/conversations/1/messages
Authorization: Bearer {token}

{
    "contenu": "Bonjour!"
}
```

#### Admin
```bash
# Obtenir les statistiques
GET /api/v1/admin/stats
Authorization: Bearer {admin_token}
Middleware: admin

# Lister les utilisateurs
GET /api/v1/admin/users
Authorization: Bearer {admin_token}
Middleware: admin
```

### 3. **Tests Vues Web**

```bash
# Dashboard
GET /dashboard
Auth: Utilisateur connecté

# Feed
GET /feed
Auth: Utilisateur connecté

# Créer une publication
GET /publications/create
Auth: Utilisateur connecté

# Groupes
GET /groupes
GET /groupes/1

# Messages
GET /messages
Auth: Utilisateur connecté

# Admin Dashboard
GET /admin
Auth: Admin uniquement
```

### 4. **Tests de Validation**

#### Form Requests
```bash
# Publication sans contenu (doit échouer)
POST /api/v1/publications
{
    "titre": "Test",
    "contenu": ""
}
# Résultat attendu: 422 Unprocessable Entity

# Publication avec contenu trop court
POST /api/v1/publications
{
    "contenu": "ab"
}
# Résultat attendu: 422 avec message "au moins 5 caractères"

# Groupe avec nom duplicata
POST /api/v1/groupes
{
    "nom": "Groupe existant",
    "visibilite": "public"
}
# Résultat attendu: 422 avec message "Ce nom existe déjà"
```

### 5. **Tests de Sécurité**

#### Autorisation
```bash
# Mettre à jour une publication d'un autre utilisateur
PUT /api/v1/publications/2
Authorization: Bearer {user1_token}
{
    "contenu": "Hacked!"
}
# Résultat attendu: 403 Non autorisé

# Supprimer un groupe qu'on n'administre pas
DELETE /api/v1/groupes/2
Authorization: Bearer {user1_token}
# Résultat attendu: 403 Non autorisé

# Accéder aux statistiques admin sans être admin
GET /api/v1/admin/stats
Authorization: Bearer {user_token}
# Résultat attendu: 403 Accès refusé
```

#### Soft Deletes
```bash
# Publier → Supprimer → Vérifier qu'elle n'apparaît pas
POST /api/v1/publications {contenu: "test"}
DELETE /api/v1/publications/1
GET /api/v1/publications
# Résultat attendu: La publication n'apparaît pas

# Vérifier qu'on peut récupérer avec onlyTrashed()
php artisan tinker
>>> \App\Models\Publication::onlyTrashed()->count()
```

### 6. **Tests de Performance**

```bash
# Vérifier les N+1 queries
GET /feed
# Vérifier dans les logs que seulement 2-3 requêtes sont faites
# (1 pour publications, 1 pour utilisateurs, 1 pour commentaires)
```

### 7. **Tests de Relations**

```php
// Dans tinker:
$user = \App\Models\Utilisateur::first();

// Vérifier les relations
$user->role // Should work
$user->publications // Should work
$user->commentaires // Should work
$user->groupes // Should work
$user->messages // Should work

// Vérifier les relations inverses
$pub = \App\Models\Publication::first();
$pub->utilisateur // Should work
$pub->utilisateur->nom // Devrait afficher le nom

$groupe = \App\Models\Groupe::first();
$groupe->utilisateurs // Should work
$groupe->admin // Should work
```

---

## 📋 Checklist de Vérification

### Base de Données
- [ ] Toutes les tables existent
- [ ] Soft deletes columns present (`deleted_at`)
- [ ] Foreign keys constraints en place
- [ ] Indexes sur les colonnes fréquemment interrogées

### Modèles
- [ ] Toutes les relations fonctionnent
- [ ] Soft deletes trait présent
- [ ] Fillable arrays correct
- [ ] Casts configurés

### Contrôleurs
- [ ] Tous les imports corrects
- [ ] Middleware appliqué sur les routes protégées
- [ ] Validation avec Form Requests
- [ ] Autorisation vérifiée

### Routes
- [ ] Toutes les routes enregistrées
- [ ] Groupes middleware appliqués
- [ ] Aliases présents (feed.index, groups.index)
- [ ] Routes API et Web séparées

### Sécurité
- [ ] Admin middleware fonctionne
- [ ] CSRF protection active
- [ ] Rate limiting présent (à vérifier)
- [ ] Validation XSS (à vérifier)

---

## 🚀 Commandes Utiles

```bash
# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Rouler les migrations
php artisan migrate --refresh

# Seeder les données de test
php artisan db:seed

# Tester une route spécifique
php artisan route:list | grep publications

# Tinker REPL
php artisan tinker
```

---

## 📊 Métriques de Succès

- ✅ Tous les endpoints API retournent le bon code HTTP
- ✅ Toutes les relations eager-loaded
- ✅ Aucune erreur N+1 queries
- ✅ Validation des Form Requests fonctionne
- ✅ Autorisation empêche l'accès non-autorisé
- ✅ Soft deletes fonctionne correctement
- ✅ Aucun modèle User/Utilisateur conflictuel

---

**Note**: Assure-toi de relancer les tests après chaque modification!
