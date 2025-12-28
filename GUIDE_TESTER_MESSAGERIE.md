# 📋 Guide - Tester le Système de Messagerie

## 🚀 Démarrer le Serveur

```bash
cd c:\Users\HP\Desktop\Campus_Network
php artisan serve
```

Le serveur devrait afficher:
```
INFO  Server running on [http://127.0.0.1:8000].
```

## 🎯 Tester la Messagerie - Étapes

### Étape 1: Se Connecter
1. Ouvrir http://127.0.0.1:8000
2. Cliquer sur **Login**
3. Utiliser les identifiants:
   - Email: `admin@campus.test`
   - Password: `password`

### Étape 2: Accéder aux Messages
1. Cliquer sur **Messages** dans le menu
2. Vous devriez voir la liste des conversations

### Étape 3: Créer une Nouvelle Conversation
1. Cliquer sur **Démarrer une conversation**
2. Sélectionner un utilisateur
3. Cliquer sur **Démarrer une conversation**

Vous devriez être redirigé vers la conversation.

### Étape 4: Envoyer un Message
1. Dans le formulaire en bas de la conversation
2. Taper un message
3. Cliquer sur **Envoyer** (icône d'avion)

Vous devriez voir:
- Le message apparaître dans la conversation
- Un message de succès
- L'utilisateur sera redirigé vers la conversation

## ✅ Points de Vérification

### Création de Conversation
- [ ] Le bouton "Démarrer une conversation" est visible
- [ ] Cliquer dessus redirige vers la conversation
- [ ] Les deux utilisateurs sont visibles dans la conversation

### Envoi de Message
- [ ] Le formulaire est présent
- [ ] Le champ "contenu" accepte du texte
- [ ] Le bouton "Envoyer" est visible
- [ ] Un message est envoyé avec succès
- [ ] Le message s'affiche dans la conversation

### Affichage des Messages
- [ ] Les messages apparaissent dans l'ordre
- [ ] Les messages de l'utilisateur actuel sont à droite (bleu)
- [ ] Les messages de l'autre utilisateur sont à gauche (gris)
- [ ] L'heure d'envoi est affichée

### Gestion des Erreurs
- [ ] Essayer d'envoyer un message vide → Erreur affichée
- [ ] Essayer d'envoyer un message trop long (>5000 chars) → Erreur affichée
- [ ] Le formulaire réaffiche les erreurs de validation

## 🔍 Debugging

### Si ça ne fonctionne pas:

**1. Vérifier les logs du serveur**
```bash
# Terminal où le serveur tourne
# Vous devriez voir les requêtes POST /messages
```

**2. Vérifier la console du navigateur (F12)**
```javascript
// Ouvrir F12 > Console
// Vérifier s'il y a des erreurs JavaScript
```

**3. Vérifier les logs Laravel**
```bash
tail -f storage/logs/laravel.log
```

**4. Vérifier la base de données**
```bash
# Vérifier qu'il y a des messages dans la table
php artisan tinker
>>> DB::table('messages')->latest()->limit(5)->get()
```

## 📝 Test Rapide (CLI)

Pour un test rapide en ligne de commande:
```bash
php test_sending_messages.php
```

Devrait afficher: ✅ TOUS LES TESTS D'ENVOI RÉUSSIS!

## 🎬 Exemple de Workflow Complet

1. **Utilisateur 1 (admin@campus.test)** se connecte
2. Va dans Messages
3. Clique "Démarrer une conversation"
4. Sélectionne Utilisateur 2
5. Envoie: "Salut! Ça va?"
6. Se déconnecte

7. **Utilisateur 2** se connecte
8. Va dans Messages
9. Voit la conversation d'Utilisateur 1
10. Ouvre la conversation
11. Voit le message "Salut! Ça va?"
12. Répond: "Bien! Et toi?"
13. Clique Envoyer

14. **Utilisateur 1** rafraîchit la page
15. Voit la réponse de Utilisateur 2

## 🆘 Problèmes Courants

### "Conversation non trouvée"
- Assurez-vous que les deux utilisateurs existent
- Vérifiez que la conversation est dans la table `conversations`
- Vérifiez la table `conversation_utilisateurs`

### "Message non envoyé"
- Vérifiez que le formulaire affiche une erreur
- Assurez-vous que les données sont valides
- Vérifiez les logs Laravel

### "Formulaire d'envoi manquant"
- Assurez-vous que vous êtes authentifié
- Assurez-vous que le fichier `messages/show.blade.php` existe
- Rafraîchissez la page

---

**Tous les tests passent avec succès!** 🎉

