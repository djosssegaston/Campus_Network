# 🚀 GUIDE UTILISATEUR - NOUVELLES FONCTIONNALITÉS

---

## 📖 TABLE DES MATIÈRES

1. [Recherche Globale](#-recherche-globale)
2. [Paramètres de Confidentialité](#-paramètres-de-confidentialité)
3. [Export RGPD](#-export-rgpd)

---

## 🔍 Recherche Globale

### Comment utiliser?

1. **Via l'interface web**:
   - Cliquez sur "Recherche" dans la barre de navigation
   - Entrez votre terme de recherche (minimum 2 caractères)
   - Sélectionnez un type de résultat (optionnel) :
     - **Tous** : Publications, Utilisateurs, Groupes
     - **Publications** : Contenu publié par les utilisateurs
     - **Utilisateurs** : Profils d'utilisateurs
     - **Groupes** : Groupes disponibles
   - Cliquez "Rechercher"

2. **Via l'API**:
   ```bash
   # Recherche tous les types
   GET /api/v1/search?q=python
   
   # Recherche par type
   GET /api/v1/search?q=python&type=publication
   
   # Pagination
   GET /api/v1/search?q=python&page=2&per_page=20
   ```

3. **Autocomplétion**:
   ```bash
   # Suggestions pour champs de recherche
   GET /api/v1/search/suggestions?q=py
   ```

### Caractéristiques

✨ **Recherche intelligente**:
- Recherche dans le contenu ET l'auteur
- Exclut les publications privées
- Résultats triés par date (plus récent en premier)
- Pagination automatique (10 résultats par page)

📊 **Types de résultats**:
- Publications avec auteur et groupe
- Utilisateurs avec filière et rôle
- Groupes avec nombre de membres

---

## 🔒 Paramètres de Confidentialité

### Où accéder?

1. Allez sur votre **Profil** (`/profile`)
2. Cliquez sur **"Gérer mes paramètres de confidentialité"**

### Quelles paramètres?

#### 1️⃣ **Visibilité du Profil**
- **Public** : Tout le monde peut voir votre profil
- **Amis seulement** : Seuls vos contacts peuvent voir
- **Privé** : Vous seul pouvez voir votre profil

#### 2️⃣ **Communications**

**Qui peut m'envoyer des messages?**
- Tout le monde
- Amis seulement
- Personne

**Qui peut voir mes publications?**
- Tout le monde (public)
- Amis seulement
- Personne (privé)

**Qui peut commenter mes publications?**
- Tout le monde
- Amis seulement
- Personne

#### 3️⃣ **Visibilité des Informations**

Toggles à activer/désactiver:
- Afficher ma liste de contacts
- Afficher mes groupes
- Afficher mon historique d'activité
- Autoriser les mentions

#### 4️⃣ **Préférences de Notifications**

Recevoir des notifications pour:
- Nouvelles demandes de contact
- Nouveaux commentaires
- Réactions sur mes publications

#### 5️⃣ **Visibilité dans les Groupes**
- Public : Tout le monde voit que vous êtes dans le groupe
- Privé : Seuls les membres du groupe vous voient

### Enregistrement

Tous les changements sont automatiquement sauvegardés et synchronisés via API.

---

## 📦 Export RGPD

### Qu'est-ce qu'un export RGPD?

C'est une **copie complète de vos données personnelles** selon vos droits RGPD (Règlement Général sur la Protection des Données).

### Comment créer un export?

1. Allez sur votre **Profil** (`/profile`)
2. Cliquez sur **"Gérer mes exports"**
3. Sélectionnez un format :
   - **JSON** : Format technique (fichier `.json`)
   - **CSV** : Format lisible Excel (fichier `.csv`)
   - **ZIP** : Archive avec JSON et CSV
4. Cliquez **"Créer l'export"**
5. Attendez le traitement (quelques secondes à minutes)

### Que contient un export?

**Votre export inclut**:
- ✅ Profil (nom, email, filière, année)
- ✅ Toutes vos publications
- ✅ Tous vos commentaires
- ✅ Tous vos messages
- ✅ Toutes vos réactions (likes)
- ✅ Vos groupes
- ✅ Vos notifications
- ✅ Vos conversations
- ✅ Vos paramètres de confidentialité

### Formats

**📄 JSON**:
- Structure hiérarchique complète
- Idéal pour traitement informatique
- Exemple:
```json
{
  "utilisateur": { "nom": "...", "email": "..." },
  "publications": [...],
  "commentaires": [...],
  "messages": [...]
}
```

**📊 CSV**:
- Format tabulaire (colonnes/lignes)
- Compatible Excel, Google Sheets
- Lisible et imprimable

**📦 ZIP**:
- Archive contenant JSON et CSV
- Téléchargement unique

### Disponibilité

- ✅ Export créé → **32 jours de disponibilité**
- ⏰ Après 32 jours → Suppression automatique
- 📥 Vous pouvez télécharger plusieurs fois
- 🗑️ Vous pouvez supprimer manuellement

### Via l'API

```bash
# Créer un export JSON
POST /api/v1/exports
{"format": "json"}

# Créer un export CSV
POST /api/v1/exports
{"format": "csv"}

# Lister vos exports
GET /api/v1/exports

# Récupérer les détails d'un export
GET /api/v1/exports/{id}

# Supprimer un export
DELETE /api/v1/exports/{id}
```

### Historique

L'interface affiche:
- ✅ Statut de chaque export
  - 🟡 En attente
  - 🔵 Traitement en cours (avec progression %)
  - 🟢 Complété
  - 🔴 Erreur
- 📅 Date de création et expiration
- 📥 Date du téléchargement
- 🎯 Actions (Télécharger, Supprimer)

---

## 🔐 Sécurité

### Informations Importantes

⚠️ **Protection de vos données**:
- Les exports contiennent toutes vos données **sans chiffrement**
- Les fichiers sont stockés sur le serveur dans `storage/exports/`
- Seul vous pouvez accéder à vos exports
- Un export peut contenir **données sensibles**

✅ **Bonnes pratiques**:
1. Téléchargez vos exports
2. Stockez-les dans un endroit sûr
3. Supprimez les exports après utilisation
4. Ne partagez jamais vos exports

---

## ❓ FAQ

### Recherche

**Q: Puis-je rechercher du contenu privé?**
A: Non. Seules les publications publiques apparaissent dans les résultats.

**Q: Puis-je chercher par utilisateur spécifique?**
A: Oui! Tapez le nom ou email dans la barre de recherche.

**Q: Combien de résultats par page?**
A: 10 résultats par défaut (configurable via API).

### Confidentialité

**Q: Mes paramètres sont-ils appliqués immédiatement?**
A: Oui! Les changements sont appliqués en temps réel.

**Q: Puis-je modifier les paramètres via l'API?**
A: Oui! PATCH `/api/v1/privacy-settings`

**Q: Que se passe-t-il si je mets tout en "privé"?**
A: Personne ne peut voir votre profil, publications, ou groupes.

### Export RGPD

**Q: Combien de temps pour exporter?**
A: Généralement < 2 minutes selon la quantité de données.

**Q: Puis-je créer plusieurs exports?**
A: Oui! Mais un seul à la fois. Attendez la fin avant de créer un autre.

**Q: Les exports incluent-ils mes données supprimées?**
A: Non, seules les données actuelles sont exportées.

**Q: Puis-je récupérer un export après 32 jours?**
A: Non, les fichiers sont automatiquement supprimés pour la conformité RGPD.

**Q: Est-ce que mon export sera fermé si je supprime mon compte?**
A: Oui, tous les exports sont supprimés quand le compte est supprimé.

---

## 📞 Support

En cas de problème:
1. Consultez ce guide
2. Vérifiez les messages d'erreur dans l'interface
3. Contactez l'administrateur

---

**Dernière mise à jour**: 26 Décembre 2025
