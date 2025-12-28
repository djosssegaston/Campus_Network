# 🔗 Points d'Entrée - Routes et Actions

## 📍 Routes Disponibles

### **Groupes - Gestion générale**

```
GET    /groupes/              → groupes.index        (Liste tous les groupes)
GET    /groupes/create        → groupes.create       (Créer un groupe)
POST   /groupes/              → groupes.store        (Stocker un nouveau groupe)
GET    /groupes/{groupe}      → groupes.show         (Voir le groupe)
```

---

### **Groupes - Messages** ⭐ NOUVEAU

```
POST   /groupes/{groupe}/messages
       → groupe-messages.store
       Créer un message avec fichiers optionnels
       
       Paramètres POST:
       - contenu (string, max 5000)
       - medias[] (array, fichiers)

DELETE /groupes/{groupe}/messages/{message}
       → groupe-messages.destroy
       Supprimer un message (auteur ou admin)
```

**Exemple HTML** :
```html
<form action="/groupes/1/messages" method="POST" enctype="multipart/form-data">
    @csrf
    <textarea name="contenu" placeholder="Votre message..." required></textarea>
    <input type="file" name="medias[]" multiple accept="image/*,video/*,audio/*">
    <button type="submit">Envoyer</button>
</form>
```

---

### **Groupes - Publications** ⭐ NOUVEAU

```
POST   /groupes/{groupe}/publications
       → groupe-publications.store
       Créer une publication avec médias optionnels
       
       Paramètres POST:
       - contenu (string, max 5000, requis)
       - medias[] (array, fichiers)

PUT    /groupes/{groupe}/publications/{publication}
       → groupe-publications.update
       Mettre à jour une publication
       
       Paramètres POST:
       - contenu (string, max 5000, requis)

DELETE /groupes/{groupe}/publications/{publication}
       → groupe-publications.destroy
       Supprimer une publication
```

**Exemple HTML - Créer une publication** :
```html
<form action="/groupes/{{ $groupe->id }}/publications" 
      method="POST" enctype="multipart/form-data">
    @csrf
    
    <textarea name="contenu" placeholder="Partagez quelque chose..." required></textarea>
    
    <input type="file" name="medias[]" multiple 
           accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.zip">
    
    <button type="submit">Publier</button>
</form>
```

**Exemple HTML - Supprimer** :
```html
<form action="/groupes/{{ $groupe->id }}/publications/{{ $publication->id }}" 
      method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Êtes-vous sûr?')">
        Supprimer
    </button>
</form>
```

---

### **Groupes - Paramètres** ⭐ NOUVEAU (Admin uniquement)

```
GET    /groupes/{groupe}/settings
       → groupe-settings.edit
       Afficher le formulaire des paramètres (admin)

PUT    /groupes/{groupe}/settings
       → groupe-settings.update
       Mettre à jour les paramètres
       
       Paramètres POST:
       - nom (string, requis)
       - description (string, nullable)
       - visibilite (enum: public|prive|secret)
       - categorie (string, nullable)
       - moderation_requise (boolean)
       - autoriser_messages (boolean)
       - autoriser_publications (boolean)
       - autoriser_medias (boolean)
       - permission_publication (enum: tous|moderateurs|admin)
       - permission_message (enum: tous|membres|admin)
       - mots_cles_interdits (string, virgules)

DELETE /groupes/{groupe}
       → groupe-settings.destroy
       Supprimer le groupe (admin uniquement)
```

**Exemple HTML - Accès aux paramètres** :
```html
@if($groupe->admin_id === auth()->id())
    <a href="/groupes/{{ $groupe->id }}/settings" class="btn btn-blue">
        ⚙️ Paramètres
    </a>
@endif
```

---

## 🔐 Contrôle d'Accès

### Messages `GroupeMessageController`
- **Store** : L'utilisateur doit être membre du groupe
- **Destroy** : Auteur ou admin du groupe

### Publications `GroupePublicationController`
- **Store** : L'utilisateur doit être membre + permissions respectées
- **Update** : Auteur ou admin
- **Destroy** : Auteur ou admin

### Paramètres `GroupeSettingController`
- **Edit** : Admin du groupe uniquement
- **Update** : Admin du groupe uniquement
- **Destroy** : Admin du groupe uniquement

---

## 📊 Vérifications de Permissions

Chaque contrôleur effectue :

```php
// Vérifier l'appartenance au groupe
if (!$groupe->utilisateurs->contains(auth()->user())) {
    return back()->with('error', 'Vous n\'êtes pas membre.');
}

// Vérifier les permissions du groupe
$settings = $groupe->getSettings();
if (!$settings->autoriser_messages) {
    return back()->with('error', 'Messages désactivés.');
}

// Vérifier les droits de suppression
if ($message->utilisateur_id !== auth()->id() 
    && $groupe->admin_id !== auth()->id()) {
    return back()->with('error', 'Permission refusée.');
}
```

---

## 📂 Structure de Stockage

Les fichiers sont stockés dans :
```
storage/public/groupes/{groupe_id}/
├── messages/
│   ├── {uuid}.jpg
│   ├── {uuid}.mp4
│   └── {uuid}.zip
└── publications/
    ├── {uuid}.png
    ├── {uuid}.mp3
    └── {uuid}.pdf
```

URL d'accès :
```
/storage/groupes/1/messages/abc-123.jpg
/storage/groupes/1/publications/def-456.mp4
```

---

## 🧪 Exemples d'Utilisation avec JavaScript

### Envoyer un message via Fetch

```javascript
async function sendMessage(groupeId) {
    const formData = new FormData();
    formData.append('contenu', document.querySelector('textarea').value);
    
    const files = document.querySelector('input[type="file"]').files;
    for (let file of files) {
        formData.append('medias[]', file);
    }
    
    const response = await fetch(`/groupes/${groupeId}/messages`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });
    
    if (response.ok) {
        location.reload();
    } else {
        alert('Erreur lors de l\'envoi');
    }
}
```

### Supprimer une publication

```javascript
async function deletePublication(groupeId, publicationId) {
    if (!confirm('Êtes-vous sûr?')) return;
    
    const response = await fetch(
        `/groupes/${groupeId}/publications/${publicationId}`,
        {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }
    );
    
    if (response.ok) {
        location.reload();
    }
}
```

---

## 💾 Données en Base de Données

### Récupérer les messages d'un groupe

```php
// Via Eloquent
$groupe = Groupe::find(1);
$messages = $groupe->messages()->with('utilisateur', 'medias')->latest()->paginate(20);

// Via SQL
SELECT * FROM groupe_messages 
WHERE groupe_id = 1 
ORDER BY created_at DESC
LIMIT 20;
```

### Récupérer les paramètres d'un groupe

```php
$settings = $groupe->getSettings(); // Récupère ou crée avec défauts
echo $settings->moderation_requise;     // true/false
echo $settings->autoriser_messages;     // true/false
echo $settings->permission_publication; // 'tous', 'moderateurs', 'admin'
```

---

## ⚡ Validations

### Messages
```
contenu : nullable, string, max 5000
medias  : nullable, array
medias.* : file, max 102400 (100 MB)
```

### Publications
```
contenu : required, string, max 5000
medias  : nullable, array
medias.* : file, max 102400 (100 MB)
```

### Paramètres
```
nom : required, string, max 255
description : nullable, string, max 2000
visibilite : required, in(public,prive,secret)
categorie : nullable, string, max 255
moderation_requise : boolean
autoriser_messages : boolean
autoriser_publications : boolean
autoriser_medias : boolean
permission_publication : required, in(tous,moderateurs,admin)
permission_message : required, in(tous,membres,admin)
mots_cles_interdits : nullable, string
```

---

## 🎯 Cas d'Utilisation

### 1. Créer une publication avec image
```
POST /groupes/1/publications
- contenu: "Regardez notre nouvelle photo!"
- medias[]: <image.jpg>
```

### 2. Envoyer un message avec vidéo
```
POST /groupes/1/messages
- contenu: "Voici la vidéo de la réunion"
- medias[]: <video.mp4>
```

### 3. Limiter qui peut publier
```
PUT /groupes/1/settings
- permission_publication: "moderateurs"
```

### 4. Modérer un groupe
```
PUT /groupes/1/settings
- moderation_requise: true
- mots_cles_interdits: "spam,pub,insulte"
```

### 5. Supprimer une publication spam
```
DELETE /groupes/1/publications/123
```

---

**API Complète Documentée** ✅
**Dernière mise à jour** : 27 Décembre 2025
