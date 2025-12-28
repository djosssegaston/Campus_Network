# ⚡ 3 COMMANDES ESSENTIELLES

## Commande 1: Lancer les migrations
```bash
php artisan migrate
```

**Résultat attendu:**
```
Creating table: medias
Migrated:  2025_12_26_000001_create_medias_table
```

---

## Commande 2: Démarrer le serveur
```bash
php artisan serve
```

**Résultat attendu:**
```
INFO  Server running on [http://127.0.0.1:8000].
```

---

## Commande 3: Tester en local
```
1. Ouvrir: http://localhost:8000/publications/create
2. Ajouter: Image/Vidéo/Son
3. Cliquer: "Publier"
4. Vérifier: http://localhost:8000/feed
```

---

**C'est tout! 🎉**

Les 3 commandes ci-dessus suffisent pour:
- ✅ Fixer l'erreur `deleted_at`
- ✅ Activer l'upload de fichiers
- ✅ Tester le système complet
