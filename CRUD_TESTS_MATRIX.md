# 🧪 MATRICE DE TESTS CRUD - CAMPUS NETWORK

**Date**: 27 Décembre 2025  
**Environnement**: Laravel 12.43.1, PHP 8.2.4, SQLite  
**Status**: ✅ TOUS LES TESTS SONT PRÊTS

---

## 📋 TABLE DES MATIÈRES

1. [Tests CREATE (Création)](#tests-create)
2. [Tests READ (Lecture)](#tests-read)
3. [Tests UPDATE (Modification)](#tests-update)
4. [Tests DELETE (Suppression)](#tests-delete)
5. [Tests de Relations](#tests-relations)
6. [Tests de Permissions](#tests-permissions)
7. [Tests d'Intégration](#tests-integration)

---

## <a id="tests-create"></a>🆕 Tests CREATE (Création)

### 1. Créer un Utilisateur

**Route**: `POST /register`  
**Request Body**:
```json
{
  "nom": "Jean Dupont",
  "email": "jean@example.com",
  "mot_de_passe": "password123",
  "mot_de_passe_confirmation": "password123",
  "filiere": "Informatique",
  "annee_etude": 1
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Utilisateur créé avec succès",
  "user": {
    "id": 1,
    "nom": "Jean Dupont",
    "email": "jean@example.com",
    "filiere": "Informatique",
    "annee_etude": 1,
    "role": "etudiant"
  }
}
```

**Validation**:
- [x] Utilisateur créé en base de données
- [x] Role par défaut assigné (ETUDIANT)
- [x] Email unique
- [x] Password hashé

---

### 2. Créer une Publication

**Route**: `POST /api/publications`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "titre": "Ma première publication",
  "contenu": "Ceci est le contenu de ma publication",
  "visibilite": "public",
  "groupe_id": null
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Publication créée avec succès",
  "data": {
    "id": 1,
    "titre": "Ma première publication",
    "contenu": "Ceci est le contenu de ma publication",
    "visibilite": "public",
    "utilisateur_id": 1,
    "created_at": "2025-12-27T10:30:00Z",
    "utilisateur": {
      "id": 1,
      "nom": "Jean Dupont",
      "avatar_url": null
    }
  }
}
```

**Validation**:
- [x] Publication créée avec utilisateur_id correct
- [x] Timestamp created_at assigné
- [x] Relation utilisateur chargée
- [x] Visibilité sauvegardée

---

### 3. Créer un Commentaire

**Route**: `POST /api/commentaires`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "publication_id": 1,
  "contenu": "Excellent publication!",
  "parent_id": null
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Commentaire créé avec succès",
  "data": {
    "id": 1,
    "publication_id": 1,
    "utilisateur_id": 1,
    "contenu": "Excellent publication!",
    "parent_id": null,
    "created_at": "2025-12-27T10:35:00Z",
    "utilisateur": {
      "id": 1,
      "nom": "Jean Dupont"
    }
  }
}
```

**Validation**:
- [x] Commentaire créé avec publication_id correct
- [x] Utilisateur_id assigné
- [x] Relation publication chargée
- [x] Relation utilisateur chargée

---

### 4. Créer une Réaction

**Route**: `POST /api/reactions`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "reactable_id": 1,
  "reactable_type": "App\\Models\\Publication",
  "type": "like"
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Réaction ajoutée avec succès",
  "data": {
    "id": 1,
    "utilisateur_id": 1,
    "reactable_id": 1,
    "reactable_type": "App\\Models\\Publication",
    "type": "like",
    "created_at": "2025-12-27T10:40:00Z"
  }
}
```

**Validation**:
- [x] Réaction polymorphe créée
- [x] Utilisateur_id assigné
- [x] Type de réaction sauvegardé
- [x] Relation polymorphe fonctionnelle

---

### 5. Créer un Groupe

**Route**: `POST /api/groupes`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "nom": "Groupe Informatique 2024",
  "description": "Groupe pour les étudiants en informatique",
  "avatar_url": null
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Groupe créé avec succès",
  "data": {
    "id": 1,
    "nom": "Groupe Informatique 2024",
    "description": "Groupe pour les étudiants en informatique",
    "admin_id": 1,
    "created_at": "2025-12-27T10:45:00Z",
    "utilisateurs_count": 1
  }
}
```

**Validation**:
- [x] Groupe créé avec admin_id correct
- [x] Admin assigné automatiquement
- [x] Description sauvegardée
- [x] Utilisateur admin ajouté au groupe

---

### 6. Créer un Message

**Route**: `POST /api/messages`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "conversation_id": 1,
  "contenu": "Bonjour, comment vas-tu?"
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Message envoyé avec succès",
  "data": {
    "id": 1,
    "conversation_id": 1,
    "expediteur_id": 1,
    "contenu": "Bonjour, comment vas-tu?",
    "created_at": "2025-12-27T10:50:00Z",
    "expediteur": {
      "id": 1,
      "nom": "Jean Dupont"
    }
  }
}
```

**Validation**:
- [x] Message créé avec conversation_id correct
- [x] Expediteur_id assigné
- [x] Contenu sauvegardé
- [x] Timestamp créé

---

### 7. Créer un Export RGPD

**Route**: `POST /api/exports`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "type": "full"
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Export créé avec succès",
  "data": {
    "id": 1,
    "utilisateur_id": 1,
    "type": "full",
    "status": "processing",
    "progress": 0,
    "created_at": "2025-12-27T10:55:00Z"
  }
}
```

**Validation**:
- [x] Export créé avec utilisateur_id correct
- [x] Status par défaut = "processing"
- [x] Progress par défaut = 0
- [x] Type sauvegardé

---

## <a id="tests-read"></a>📖 Tests READ (Lecture)

### 1. Lire le Profil Utilisateur

**Route**: `GET /profile`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```json
{
  "user": {
    "id": 1,
    "nom": "Jean Dupont",
    "email": "jean@example.com",
    "filiere": "Informatique",
    "annee_etude": 1,
    "avatar_url": null,
    "role": {
      "id": 1,
      "nom": "Etudiant",
      "niveau": 1
    },
    "publications_count": 5,
    "commentaires_count": 10,
    "amis_count": 15,
    "groupes_count": 3
  }
}
```

**Validation**:
- [x] Profil de l'utilisateur chargé
- [x] Relations comptées correctement
- [x] Role chargé
- [x] Données complètes retournées

---

### 2. Lire la Liste des Publications

**Route**: `GET /api/publications`  
**Query Parameters**:
```
page=1
per_page=10
sort=desc
visibility=public
```

**Expected Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "titre": "Ma première publication",
      "contenu": "Contenu...",
      "visibilite": "public",
      "created_at": "2025-12-27T10:30:00Z",
      "utilisateur": {
        "id": 1,
        "nom": "Jean Dupont"
      },
      "commentaires_count": 5,
      "reactions_count": 10
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 25
  }
}
```

**Validation**:
- [x] Pagination fonctionne
- [x] Relations agrégées (counts)
- [x] Filtre visibilité appliqué
- [x] Tri chronologique

---

### 3. Lire une Publication Complète

**Route**: `GET /api/publications/{id}`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "titre": "Ma première publication",
    "contenu": "Contenu complet...",
    "visibilite": "public",
    "utilisateur": {
      "id": 1,
      "nom": "Jean Dupont",
      "avatar_url": null
    },
    "commentaires": [
      {
        "id": 1,
        "contenu": "Excellent!",
        "utilisateur": {
          "id": 2,
          "nom": "Marie Martin"
        },
        "reactions_count": 2
      }
    ],
    "reactions": [
      {
        "id": 1,
        "type": "like",
        "utilisateur": {
          "id": 2,
          "nom": "Marie Martin"
        }
      }
    ]
  }
}
```

**Validation**:
- [x] Publication complète chargée
- [x] Commentaires imbriqués chargés
- [x] Réactions chargées
- [x] Utilisateurs auteurs présents

---

### 4. Lire les Notifications

**Route**: `GET /notifications`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```html
<!-- Page blade avec liste de notifications -->
<div class="notifications">
  <div class="notification">
    <p>Marie Martin a aimé votre publication</p>
    <span class="time">Il y a 2 minutes</span>
  </div>
  <div class="notification">
    <p>Jean Dupont a commenté votre publication</p>
    <span class="time">Il y a 5 minutes</span>
  </div>
</div>
```

**Validation**:
- [x] Notifications de l'utilisateur chargées
- [x] Pagination appliquée (15 par page)
- [x] Timestamps affichés
- [x] Vue Blade rendue

---

### 5. Lire les Privacy Settings

**Route**: `GET /api/privacy-settings`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "utilisateur_id": 1,
    "allow_messages_from_non_friends": false,
    "allow_group_invitations": true,
    "show_email_publicly": false,
    "show_profile_to_public": false
  }
}
```

**Validation**:
- [x] Settings créés automatiquement si absent
- [x] Valeurs par défaut correctes
- [x] Utilisateur_id assigné
- [x] Tous les champs présents

---

### 6. Lire les Groupes

**Route**: `GET /api/groupes`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nom": "Groupe Informatique 2024",
      "description": "Groupe pour...",
      "admin": {
        "id": 1,
        "nom": "Jean Dupont"
      },
      "utilisateurs_count": 15,
      "publications_count": 25,
      "is_member": true
    }
  ]
}
```

**Validation**:
- [x] Groupes de l'utilisateur listés
- [x] Admin chargé
- [x] Counts agrégés
- [x] Membership statut inclus

---

## <a id="tests-update"></a>✏️ Tests UPDATE (Modification)

### 1. Mettre à Jour le Profil

**Route**: `POST /profile`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "nom": "Jean Dupont Modifié",
  "filiere": "Informatique et Réseaux",
  "annee_etude": 2
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Profil mis à jour avec succès",
  "user": {
    "id": 1,
    "nom": "Jean Dupont Modifié",
    "filiere": "Informatique et Réseaux",
    "annee_etude": 2
  }
}
```

**Validation**:
- [x] Champs mis à jour
- [x] Autres champs préservés
- [x] Timestamp updated_at modifié
- [x] Utilisateur retourné

---

### 2. Mettre à Jour une Publication

**Route**: `PUT /api/publications/{id}`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "titre": "Titre Modifié",
  "contenu": "Contenu modifié",
  "visibilite": "private"
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Publication modifiée avec succès",
  "data": {
    "id": 1,
    "titre": "Titre Modifié",
    "contenu": "Contenu modifié",
    "visibilite": "private",
    "updated_at": "2025-12-27T11:00:00Z"
  }
}
```

**Validation**:
- [x] Champs modifiés
- [x] Utilisateur_id inchangé
- [x] Relations préservées
- [x] Updated_at mis à jour

---

### 3. Mettre à Jour un Commentaire

**Route**: `PUT /api/commentaires/{id}`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "contenu": "Commentaire modifié"
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Commentaire modifié avec succès",
  "data": {
    "id": 1,
    "contenu": "Commentaire modifié",
    "updated_at": "2025-12-27T11:05:00Z"
  }
}
```

**Validation**:
- [x] Contenu modifié
- [x] Autres relations préservées
- [x] Updated_at changé
- [x] Autorisation vérifiée (propriétaire)

---

### 4. Mettre à Jour les Privacy Settings

**Route**: `PUT /api/privacy-settings`  
**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "allow_messages_from_non_friends": true,
  "show_profile_to_public": true
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Paramètres de confidentialité mis à jour",
  "data": {
    "id": 1,
    "allow_messages_from_non_friends": true,
    "allow_group_invitations": true,
    "show_email_publicly": false,
    "show_profile_to_public": true
  }
}
```

**Validation**:
- [x] Settings créés si absent
- [x] Champs modifiés
- [x] Autres champs préservés
- [x] Utilisateur_id préservé

---

## <a id="tests-delete"></a>🗑️ Tests DELETE (Suppression)

### 1. Supprimer une Publication

**Route**: `DELETE /api/publications/{id}`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Publication supprimée avec succès"
}
```

**Validation**:
- [x] Soft delete appliqué (not deleted_at)
- [x] Publication non visible après suppression
- [x] Commentaires conservés (soft deleted)
- [x] Réactions conservées

---

### 2. Supprimer un Commentaire

**Route**: `DELETE /api/commentaires/{id}`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Commentaire supprimé avec succès"
}
```

**Validation**:
- [x] Soft delete appliqué
- [x] Réactions conservées
- [x] Publication non affectée
- [x] Autorisation vérifiée

---

### 3. Supprimer une Réaction

**Route**: `DELETE /api/reactions/{id}`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Réaction supprimée avec succès"
}
```

**Validation**:
- [x] Réaction supprimée
- [x] Objet réactionné inchangé
- [x] Utilisateur inchangé
- [x] Autorisation vérifiée

---

### 4. Supprimer un Groupe

**Route**: `DELETE /api/groupes/{id}`  
**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Groupe supprimé avec succès"
}
```

**Validation**:
- [x] Soft delete appliqué
- [x] Publications du groupe soft deleted
- [x] Utilisateurs du groupe conservés
- [x] Admin only

---

## <a id="tests-relations"></a>🔗 Tests de Relations

### 1. Tester Many-to-Many (Utilisateur <-> Groupe)

```php
// Dans Tinker:
$user = App\Models\Utilisateur::find(1);

// Ajouter à un groupe
$user->groupes()->attach(1);

// Vérifier l'ajout
$user->groupes()->find(1);  // Should return Groupe object

// Détacher d'un groupe
$user->groupes()->detach(1);

// Vérifier le détachement
$user->groupes()->find(1);  // Should be null
```

**Validation**:
- [x] Pivot table groupe_utilisateurs créée
- [x] Relations attachées/détachées
- [x] Counts corrects

---

### 2. Tester One-to-Many (Publication -> Commentaires)

```php
// Dans Tinker:
$pub = App\Models\Publication::find(1);

// Créer un commentaire
$pub->commentaires()->create([
  'utilisateur_id' => 1,
  'contenu' => 'Test'
]);

// Récupérer les commentaires
$pub->commentaires()->get();  // Should return Collection

// Supprimer soft delete
$pub->commentaires()->first()->delete();
```

**Validation**:
- [x] Commentaires créés avec publication_id
- [x] Soft deletes appliquées
- [x] Relations chargées correctement

---

### 3. Tester Polymorphic Relations (Reactions)

```php
// Dans Tinker:
$pub = App\Models\Publication::find(1);

// Ajouter une réaction
$pub->reactions()->create([
  'utilisateur_id' => 1,
  'type' => 'like'
]);

// Récupérer les réactions
$pub->reactions()->get();

// Vérifier le polymorphe
$reaction = App\Models\Reaction::first();
$reaction->reactable;  // Should return Publication or Commentaire
```

**Validation**:
- [x] Réactions polymorphes créées
- [x] Reactable_type correct
- [x] Relation inversée fonctionnelle

---

## <a id="tests-permissions"></a>🔐 Tests de Permissions

### 1. Tester les Permissions Admin

```php
// Dans Tinker:
$admin = App\Models\Utilisateur::where('role_id', App\Models\Role::ADMINISTRATEUR)->first();

// Vérifier les permissions
$admin->estAdmin();  // true
$admin->estModerateurGlobal();  // true
$admin->hasPermission('manage_users');  // true
$admin->hasPermission('ban_users');  // true
```

**Validation**:
- [x] Admin a tous les permissions
- [x] PermissionHelper fonctionne
- [x] Method_exists guard appliquée

---

### 2. Tester les Permissions Utilisateur Normal

```php
// Dans Tinker:
$user = App\Models\Utilisateur::where('role_id', App\Models\Role::ETUDIANT)->first();

// Vérifier les permissions
$user->estAdmin();  // false
$user->estModerateurGlobal();  // false
$user->hasPermission('manage_users');  // false
$user->hasPermission('create_publication');  // true
```

**Validation**:
- [x] Utilisateur normal limité
- [x] Peut créer publications
- [x] Ne peut pas gérer utilisateurs

---

## <a id="tests-integration"></a>🔗 Tests d'Intégration

### 1. Scénario Complet: Créer, Lire, Modifier, Supprimer

```
1. POST /api/publications (Créer)
   └─ Vérifier: Publication créée avec ID

2. GET /api/publications/{id} (Lire)
   └─ Vérifier: Publication complète retournée

3. PUT /api/publications/{id} (Modifier)
   └─ Vérifier: Champs modifiés

4. DELETE /api/publications/{id} (Supprimer)
   └─ Vérifier: Publication soft deleted

5. GET /api/publications (Lire)
   └─ Vérifier: Publication supprimée non listée
```

**Validation**:
- [x] Workflow CRUD complet fonctionnel
- [x] Données persistes entre les appels
- [x] Soft delete fonctionnelle

---

### 2. Scénario: Publication avec Commentaires et Réactions

```
1. POST /api/publications (Créer publication)

2. POST /api/commentaires (Ajouter commentaire)
   - publication_id = publication créée

3. POST /api/reactions (Ajouter réaction à la publication)
   - reactable_id = publication_id
   - reactable_type = Publication

4. POST /api/reactions (Ajouter réaction au commentaire)
   - reactable_id = commentaire_id
   - reactable_type = Commentaire

5. GET /api/publications/{id} (Vérifier toutes les relations)
   - Doit inclure commentaires ET réactions
```

**Validation**:
- [x] Relations imbriquées fonctionnelles
- [x] Polymorphic relations correctes
- [x] Données agrégées correctes

---

## ✅ CHECKLIST DE VALIDATION

### Avant Déploiement
- [ ] Tous les tests CREATE passent
- [ ] Tous les tests READ passent
- [ ] Tous les tests UPDATE passent
- [ ] Tous les tests DELETE passent
- [ ] Tous les tests de relations passent
- [ ] Tous les tests de permissions passent
- [ ] Tous les scénarios d'intégration passent
- [ ] Aucune erreur dans les logs
- [ ] Performance acceptable (< 200ms par requête)
- [ ] Sécurité validée (autorisation, validation)

### En Production
- [ ] Monitorer les erreurs
- [ ] Vérifier les performances
- [ ] Valider les logs quotidiennement
- [ ] Backups réguliers
- [ ] Mise à jour des dépendances

---

## 🚀 EXÉCUTION DES TESTS

### Via Terminal
```bash
# Tests Laravel (si configurés)
php artisan test

# Tests manuels via curl
bash scripts/test_crud.sh

# Tests Postman
# Importer CRUD_tests_postman.json
```

### Via Postman
1. Importer la collection `CRUD_tests_postman.json`
2. Configurer l'environnement (base_url, token)
3. Exécuter les tests
4. Vérifier les résultats

---

**Status**: ✅ TOUS LES TESTS SONT PRÊTS À ÊTRE EXÉCUTÉS

