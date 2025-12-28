# 📖 Guide d'Utilisation - Groupes Améliorés

## 🎯 Nouvelles Fonctionnalités Disponibles

Vous pouvez maintenant :
1. ✅ **Créer des publications** dans vos groupes avec texte et médias
2. ✅ **Envoyer des messages** de groupe (texte + fichiers)
3. ✅ **Partager des contenus multimédias** (images, vidéos, audio, fichiers)
4. ✅ **Gérer les paramètres** de votre groupe (si vous êtes admin)
5. ✅ **Modérer les contenus** avec des filtres de mots-clés

---

## 👥 Rôles et Permissions

### Membre du groupe
- ✅ Peut lire les publications et messages
- ✅ Peut créer des publications (sauf si admin a restreint)
- ✅ Peut envoyer des messages (sauf si admin a restreint)
- ✅ Peut partager des médias (sauf si admin a désactivé)
- ❌ Ne peut pas modifier les paramètres du groupe

### Modérateur
- ✅ Droits des membres
- ✅ Peut toujours publier (même si restreint)
- ✅ Peut supprimer les contenus non appropriés
- ❌ Ne peut pas changer les paramètres

### Administrateur
- ✅ Tous les droits
- ✅ Accès aux paramètres du groupe
- ✅ Suppression du groupe
- ✅ Gestion des permissions
- ✅ Modération et filtres

---

## 📝 Publier dans un Groupe

### Étape 1 : Accédez au groupe
1. Allez dans **Groupes** → Sélectionnez un groupe
2. Vous devez être **membre du groupe**

### Étape 2 : Remplissez le formulaire
1. Cliquez sur la section **"Créer une publication"** (en bleu)
2. Écrivez votre message dans le champ texte
3. (Optionnel) Ajoutez des fichiers en cliquant sur **"Ajouter des médias"**

### Étape 3 : Sélectionnez les fichiers
Vous pouvez ajouter :
- **Images** : PNG, JPG, GIF, WebP (max 100 MB)
- **Vidéos** : MP4, WebM, OGG (max 100 MB)
- **Audio** : MP3, WAV, M4A (max 100 MB)
- **Documents** : PDF, Word, Excel, ZIP (max 100 MB)

### Étape 4 : Publiez
1. Cliquez sur **"Publier"**
2. Votre publication apparaît immédiatement (ou en attente si modération)

**Exemple** :
```
Titre : Annonce importante
Texte : Réunion du groupe le 28/12 à 15h
Média : photo_reunion.jpg
→ [Publier]
```

---

## 💬 Envoyer des Messages

### Format
Les messages de groupe supportent :
- ✅ Texte simple
- ✅ Images et vidéos
- ✅ Fichiers audio
- ✅ Documents

### Utilisation
1. Accédez à la **section Messages du groupe**
2. Remplissez le message
3. Attachez des fichiers si besoin
4. Cliquez **"Envoyer"**

**Limite** : Max 100 MB par fichier, illimité en nombre de fichiers

---

## 🎥 Partager des Médias

### Images
- Format : JPG, PNG, GIF, WebP
- Max : 100 MB
- Affichage : Galerie inline avec aperçu

### Vidéos
- Format : MP4, WebM, OGG
- Max : 100 MB
- Affichage : Lecteur vidéo intégré avec `<video>`

### Audio
- Format : MP3, WAV, M4A
- Max : 100 MB
- Affichage : Lecteur audio avec contrôles

### Fichiers
- Format : PDF, DOC, DOCX, XLS, XLSX, ZIP
- Max : 100 MB
- Affichage : Icône téléchargeable

---

## ⚙️ Gérer un Groupe (Admin)

### Accès aux paramètres
1. Allez dans votre groupe
2. Cliquez sur **"⚙️ Paramètres"** (coin supérieur droit, visible si admin)
3. Vous arrivez à la page de configuration

### Modifier les informations générales

**Nom du groupe**
- Nom visible par tous
- Max 255 caractères

**Description**
- Explique le but du groupe
- Visible dans la liste des groupes
- Max 2000 caractères

**Catégorie**
- Ex : "Tech", "Loisir", "Sport"
- Aide au classement

**Visibilité**
- 🌐 **Public** : Tous peuvent voir et rejoindre
- 🔒 **Privé** : Sur invitation uniquement
- 🔐 **Secret** : Invisible dans les recherches

### Gérer les Permissions

**Qui peut publier ?**
- ✅ Tous les membres
- 📢 Modérateurs et admin
- 👤 Admin uniquement

**Qui peut envoyer des messages ?**
- ✅ Tous les membres
- ✔️ Membres confirmés
- 👤 Admin uniquement

### Fonctionnalités à activer/désactiver

- ✅ **Autoriser les messages** : Les membres peuvent envoyer des messages
- ✅ **Autoriser les publications** : Les membres peuvent publier du contenu
- ✅ **Autoriser les médias** : Les fichiers peuvent être attachés
- ✅ **Modération requise** : Approuver chaque publication avant affichage

### Modération et Filtres

**Mots-clés interdits**
1. Allez dans la section **"Modération"**
2. Entrez les mots à filtrer (séparés par des virgules)
   ```
   spam, insulte, publicité
   ```
3. Les messages contenant ces mots seront signalés

### Supprimer le groupe

⚠️ **Zone de danger**
1. Scrollez en bas de la page
2. Cliquez **"Supprimer le groupe"**
3. Confirmez deux fois
4. **Attention** : Toutes les données seront supprimées définitivement

---

## 🛡️ Sécurité

### Suppression de contenu
- Vous pouvez supprimer vos propres publications
- L'admin peut supprimer toutes les publications
- Les fichiers sont supprimés du serveur aussi

### Suppression de compte
- Si vous supprimez votre compte, vos publications restent (pseudonymisées)
- Les messages de groupe restent visibles

### Confidentialité
- Les fichiers sont stockés dans `/storage/public/groupes/`
- Accessibles que aux membres du groupe
- Les mots-clés ne suppriment pas automatiquement (signalement manuel)

---

## ❓ FAQ

### Q : Je ne peux pas publier. Pourquoi ?
**R** : L'admin a peut-être :
- Restreint les droits de publication aux modérateurs/admin
- Désactivé les publications
- Paramétré une modération obligatoire

**Solution** : Contactez l'admin du groupe

### Q : Quelle est la taille limite des fichiers ?
**R** : 100 MB par fichier, illimité en nombre

### Q : Mes fichiers sont supprimés après combien de temps ?
**R** : Les fichiers sont stockés indéfiniment sauf si :
- Vous supprimez la publication
- L'admin supprime votre message
- Le groupe est supprimé

### Q : Je suis admin. Comment modérer un message ?
**R** : Pour chaque publication :
1. Cliquez sur la **poubelle** (coin droit)
2. Confirmez la suppression
3. Le contenu est supprimé

### Q : Puis-je modifier une publication après l'avoir publiée ?
**R** : Oui, si vous êtes propriétaire ou admin (à ajouter à votre panel)

### Q : Comment ajouter des modérateurs ?
**R** : Actuellement, l'admin doit faire une demande techniques ou les modifier via SQL

---

## 🚀 Astuces

1. **Aperçu avant partage** : Vérifiez le format de votre fichier
2. **Description claire** : Décrivez votre groupe en détail
3. **Modération active** : Mettez des filtres pertinents
4. **Catégorie utile** : Aidez les utilisateurs à trouver votre groupe
5. **Règles dans la description** : Expliquez les attentes

---

## 📞 Support

Pour tout problème :
1. Vérifiez que vous êtes membre du groupe
2. Vérifiez les permissions du groupe
3. Essayez un autre navigateur
4. Contactez l'administrateur du groupe

---

**Dernière mise à jour** : 27 Décembre 2025
**Version** : 1.0 Complète
