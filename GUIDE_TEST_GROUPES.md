# 🧪 Guide de Test - Groupes Améliorés

## 📋 Avant de Commencer

✅ Application en cours d'exécution (`php artisan serve`)  
✅ Utilisateur authentifié  
✅ Au moins un groupe créé  
✅ Au moins un utilisateur membre du groupe  

---

## 🎯 Tests à Effectuer

### Test 1️⃣ : Créer une Publication Simple

**Objectif** : Vérifier que les publications fonctionnent

```
1. Allez dans un groupe (en tant que membre)
2. Scrollez vers "Créer une publication"
3. Remplissez le formulaire:
   - Contenu: "Ceci est un test"
4. Cliquez "Publier"
5. Vérifiez que la publication apparaît
```

**Résultat attendu** : ✅ Publication visible immédiatement

---

### Test 2️⃣ : Publier avec une Image

**Objectif** : Vérifier upload et affichage d'images

```
1. Allez dans un groupe
2. Cliquez sur "Créer une publication"
3. Remplissez:
   - Contenu: "Voici mon image"
   - Fichiers: Sélectionnez une image (JPG/PNG)
4. Cliquez "Publier"
5. Vérifiez que l'image s'affiche
```

**Résultat attendu** : ✅ Image dans une galerie

---

### Test 3️⃣ : Publier avec une Vidéo

**Objectif** : Vérifier upload et lecture vidéo

```
1. Allez dans un groupe
2. Créez une publication:
   - Contenu: "Regardez ma vidéo"
   - Fichiers: Vidéo MP4 (petit fichier pour test)
3. Cliquez "Publier"
4. Cliquez sur "Play" dans la vidéo
```

**Résultat attendu** : ✅ Vidéo joue en ligne

---

### Test 4️⃣ : Publier avec Audio

**Objectif** : Vérifier upload et lecture audio

```
1. Allez dans un groupe
2. Créez une publication:
   - Contenu: "Écoutez ça"
   - Fichiers: Audio MP3
3. Cliquez "Publier"
4. Testez les contrôles audio
```

**Résultat attendu** : ✅ Audio joue avec contrôles

---

### Test 5️⃣ : Publier avec Fichier

**Objectif** : Vérifier upload de documents

```
1. Allez dans un groupe
2. Créez une publication:
   - Contenu: "Document PDF"
   - Fichiers: PDF ou ZIP
3. Cliquez "Publier"
4. Cliquez sur le fichier pour télécharger
```

**Résultat attendu** : ✅ Fichier téléchargeable

---

### Test 6️⃣ : Publier Plusieurs Fichiers

**Objectif** : Vérifier upload multiple

```
1. Allez dans un groupe
2. Créez une publication:
   - Contenu: "Album photos"
   - Fichiers: Sélectionnez 3-4 images
3. Cliquez "Publier"
4. Vérifiez que toutes les images s'affichent
```

**Résultat attendu** : ✅ Galerie avec 3-4 images

---

### Test 7️⃣ : Supprimer une Publication

**Objectif** : Vérifier suppression

```
1. Allez dans un groupe
2. Trouvez une publication que vous avez créée
3. Cliquez sur l'icône poubelle (coin droit)
4. Confirmez la suppression
5. Vérifiez que la publication a disparu
```

**Résultat attendu** : ✅ Publication supprimée avec fichiers

---

### Test 8️⃣ : Accéder aux Paramètres (Admin)

**Objectif** : Vérifier access au panel admin

```
1. Allez dans un groupe (en tant qu'admin)
2. Regardez en haut à droite
3. Cliquez sur "⚙️ Paramètres"
4. Vérifiez que vous êtes redirigé
```

**Résultat attendu** : ✅ Page paramètres visible

---

### Test 9️⃣ : Modifier Permissions (Admin)

**Objectif** : Vérifier gestion des permissions

```
1. Accédez à Settings
2. Trouvez "Qui peut publier ?"
3. Changez en "Admin uniquement"
4. Cliquez "Enregistrer"
5. Allez au groupe
6. Essayez de publier (en tant que membre)
```

**Résultat attendu** : ✅ Message d'erreur de permission

---

### Test 🔟 : Activer Modération (Admin)

**Objectif** : Vérifier modération

```
1. Accédez à Settings
2. Cochez "Modération requise"
3. Cliquez "Enregistrer"
4. Allez au groupe
5. Publiez quelque chose
6. Vérifiez le statut
```

**Résultat attendu** : ✅ Publication en attente d'approbation

---

## 🔄 Scénarios Complets

### Scénario A : Utilisateur Normal

```
1. Créer une publication ✅
2. Ajouter une image ✅
3. Voir les réactions ✅
4. Supprimer la publication ✅
```

### Scénario B : Administrateur

```
1. Accéder aux paramètres ✅
2. Modifier les permissions ✅
3. Activer modération ✅
4. Ajouter filtres ✅
5. Supprimer un contenu ✅
6. Supprimer le groupe ✅
```

### Scénario C : Multimédia

```
1. Publier image ✅
2. Publier vidéo ✅
3. Publier audio ✅
4. Publier fichier ✅
5. Publier tout ensemble ✅
```

---

## ⚠️ Cas d'Erreur à Tester

### Cas 1 : Fichier Trop Grand
```
Fichier > 100 MB
→ Résultat : Message d'erreur
```

### Cas 2 : Type Non Autorisé
```
Fichier exécutable (.exe, .bat)
→ Résultat : Message d'erreur
```

### Cas 3 : Non-Membre Accède
```
Utilisateur non-membre tente de publier
→ Résultat : Message d'erreur
```

### Cas 4 : Non-Admin Accède Settings
```
Utilisateur non-admin va à /groupes/{id}/settings
→ Résultat : Erreur 403
```

### Cas 5 : Contenu Vide
```
Publie sans texte ni fichier
→ Résultat : Message d'erreur de validation
```

---

## 📊 Checklist de Validation

### Fonctionnalités
- [ ] Créer publication ✓
- [ ] Ajouter image ✓
- [ ] Ajouter vidéo ✓
- [ ] Ajouter audio ✓
- [ ] Ajouter fichier ✓
- [ ] Supprimer publication ✓
- [ ] Envoyer message ✓
- [ ] Voir paramètres (admin) ✓
- [ ] Modifier paramètres (admin) ✓
- [ ] Supprimer groupe (admin) ✓

### Interface
- [ ] Formulaire visible ✓
- [ ] Boutons fonctionnent ✓
- [ ] Messages d'erreur clairs ✓
- [ ] Médias s'affichent ✓
- [ ] Design responsive ✓

### Sécurité
- [ ] CSRF protégé ✓
- [ ] Auth vérifiée ✓
- [ ] Permissions contrôlées ✓
- [ ] Fichiers sécurisés ✓
- [ ] Validation active ✓

### Performance
- [ ] Upload rapide ✓
- [ ] Affichage fluide ✓
- [ ] Pas de timeouts ✓
- [ ] Pagination OK ✓

---

## 🐛 Dépannage

### "Vous n'êtes pas membre de ce groupe"
**Solution** : Rejoindre le groupe d'abord

### "Fichier trop volumineux"
**Solution** : Utiliser un fichier < 100 MB

### "Type MIME non accepté"
**Solution** : Utiliser les types acceptés (jpg, png, mp4, mp3, pdf, doc)

### "Permission refusée"
**Solution** : Vérifier que vous êtes admin pour les paramètres

### "Les publications sont désactivées"
**Solution** : Admin doit cocher "Autoriser les publications"

---

## 📝 Rapport de Test

Après avoir effectué les tests, remplissez ce rapport :

```markdown
Date du test : ___________
Testeur : ___________

Résultats :
- Publications : [PASS/FAIL]
- Images : [PASS/FAIL]
- Vidéos : [PASS/FAIL]
- Audio : [PASS/FAIL]
- Fichiers : [PASS/FAIL]
- Suppression : [PASS/FAIL]
- Admin Panel : [PASS/FAIL]
- Permissions : [PASS/FAIL]
- Modération : [PASS/FAIL]
- Sécurité : [PASS/FAIL]

Bugs trouvés :
(Aucun/Lisez ci-dessous)

Améliorations suggérées :
```

---

## ✨ Tests Manuels Détaillés

### Test Détaillé : Publication Complète

```
ÉTAPE 1: Préparation
  - Ouvrir un groupe
  - Vérifier que vous êtes membre
  - Préparer les fichiers de test

ÉTAPE 2: Créer la publication
  - Cliquer sur "Créer une publication"
  - Vérifier que le formulaire s'affiche

ÉTAPE 3: Remplir le formulaire
  - Écrire un texte de test
  - Ajouter 3 fichiers : image, vidéo, audio

ÉTAPE 4: Valider
  - Cliquer "Publier"
  - Observer la redirection

ÉTAPE 5: Vérification
  - Vérifier que la publication apparaît
  - Vérifier que les fichiers s'affichent
  - Vérifier les types d'affichage

ÉTAPE 6: Nettoyage
  - Cliquer poubelle
  - Confirmer suppression
  - Vérifier la suppression
```

---

## 🎬 Screencast Suggestions

Pour documenter visuellement :

1. **Créer une publication** (30 sec)
2. **Ajouter une image** (30 sec)
3. **Accéder aux paramètres** (30 sec)
4. **Changer une permission** (30 sec)
5. **Supprimer une publication** (20 sec)

---

## 🎓 Commandes Utiles (CLI)

Pour tester en ligne de commande :

```bash
# Voir les routes créées
php artisan route:list | grep groupe

# Vérifier la migration
php artisan migrate:status

# Tester un modèle
php artisan tinker

# Vider le cache
php artisan optimize:clear
```

---

**Test Guide Version** : 1.0  
**Date** : 27 Décembre 2025  
**Statut** : Prêt à tester
