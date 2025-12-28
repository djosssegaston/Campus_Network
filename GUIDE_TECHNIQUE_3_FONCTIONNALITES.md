# Guide Technique - Intégration des 3 Fonctionnalités

## 📊 Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    UTILISATEUR                              │
└──────────────────────┬──────────────────────────────────────┘
                       │
        ┌──────────────┼──────────────┐
        │              │              │
        ▼              ▼              ▼
    ┌───────┐    ┌────────┐    ┌──────────┐
    │ FEED  │    │GROUPES │    │NOTIF'S   │
    └───┬───┘    └────┬───┘    └──────────┘
        │             │              ▲
        │ Partage     │ Join/Leave   │ Create
        ▼             ▼              │
    ┌─────────────────────────────────┐
    │      CONTROLLERS                │
    ├─────────────────────────────────┤
    │ - PublicationPartageController  │
    │ - GroupeMembreController        │
    │ - NotificationController        │
    └─────────┬───────────────────────┘
              │
        ┌─────┴─────┐
        ▼           ▼
    ┌────────┐  ┌──────────┐
    │ MODELS │  │ DATABASE │
    │ ────── │  │ ──────── │
    │Partage │  │ partages │
    │Groupe  │  │ groupe_  │
    │        │  │ utilisa- │
    │        │  │ teurs    │
    │Notif   │  │ notif-   │
    │        │  │ ications │
    └────────┘  └──────────┘
```

## 🔌 Points d'Extension

### 1. Ajouter un nouveau type de notification

**Fichier:** `app/Http/Controllers/PublicationPartageController.php`

```php
// Exemple: Notifier lors d'un nouveau commentaire
Notification::envoyer(
    $publication->utilisateur,
    'commentaire_sur_publication',  // Nouveau type
    [
        'publication_id' => $publication->id,
        'utilisateur_id' => auth()->id(),
        'utilisateur_nom' => auth()->user()->name,
        'commentaire' => $commentaire->contenu,
        'message' => auth()->user()->name . " a commenté votre publication"
    ]
);
```

**Fichier:** `resources/views/notifications/index.blade.php`

```php
@elseif($notification->type === 'commentaire_sur_publication')
    <div class="flex items-center gap-2">
        <i class="fas fa-comment text-yellow-500"></i>
        <p class="font-semibold text-gray-900">Nouveau commentaire</p>
    </div>
    <p class="text-sm text-gray-600 mt-1">{{ $notification->donnees['message'] ?? 'Vous avez reçu un nouveau commentaire' }}</p>
```

### 2. Ajouter une action de partage avancée

**Créer un nouveau modèle:**

```php
// app/Models/PartagePersonnalise.php
class PartagePersonnalise extends Model
{
    protected $table = 'partages_personnalises';
    protected $fillable = ['utilisateur_id', 'publication_id', 'message', 'groupe_id'];
    
    public function utilisateur() { return $this->belongsTo(Utilisateur::class); }
    public function publication() { return $this->belongsTo(Publication::class); }
    public function groupe() { return $this->belongsTo(Groupe::class); }
}
```

**Créer une migration:**

```php
Schema::create('partages_personnalises', function (Blueprint $table) {
    $table->id();
    $table->foreignId('utilisateur_id')->constrained('utilisateurs')->cascadeOnDelete();
    $table->foreignId('publication_id')->constrained('publications')->cascadeOnDelete();
    $table->foreignId('groupe_id')->nullable()->constrained('groupes')->nullOnDelete();
    $table->text('message')->nullable();
    $table->timestamps();
});
```

### 3. Ajouter des permissions de groupe avancées

**Modifier le modèle GroupeSetting:**

```php
// app/Models/GroupeSetting.php
protected $fillable = [
    'groupe_id',
    'moderation_requise',
    'autoriser_messages',
    'autoriser_publications',
    'autoriser_medias',
    'autoriser_partages',  // NEW
    'permission_publication',
    'permission_message',
    'permission_partage',  // NEW
];
```

**Ajouter une vérification dans le contrôleur:**

```php
// app/Http/Controllers/PublicationPartageController.php
public function store(Publication $publication): RedirectResponse
{
    $settings = $publication->groupe?->getSettings();
    
    // Vérifier les permissions
    if ($settings && !$settings->autoriser_partages) {
        return redirect()->back()->with('error', 'Les partages ne sont pas autorisés dans ce groupe');
    }
    
    // ... reste du code
}
```

## 🧪 Tests Unitaires

### Test 1: Partage de Publication

```php
// tests/Feature/PublicationPartageTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Utilisateur;
use App\Models\Publication;

class PublicationPartageTest extends TestCase
{
    public function test_utilisateur_peut_partager_publication()
    {
        $user = Utilisateur::factory()->create();
        $publication = Publication::factory()->create();
        
        $this->actingAs($user)->post(route('partages.store', $publication))
            ->assertRedirect();
        
        $this->assertDatabaseHas('partages', [
            'utilisateur_id' => $user->id,
            'publication_id' => $publication->id,
        ]);
    }
    
    public function test_partage_est_bascule()
    {
        $user = Utilisateur::factory()->create();
        $publication = Publication::factory()->create();
        
        // Premier partage
        $this->actingAs($user)->post(route('partages.store', $publication));
        $this->assertDatabaseHas('partages', [
            'utilisateur_id' => $user->id,
            'publication_id' => $publication->id,
        ]);
        
        // Deuxième partage = suppression
        $this->actingAs($user)->post(route('partages.store', $publication));
        $this->assertDatabaseMissing('partages', [
            'utilisateur_id' => $user->id,
            'publication_id' => $publication->id,
        ]);
    }
}
```

### Test 2: Rejoindre Groupe

```php
// tests/Feature/GroupeMembreTest.php
public function test_utilisateur_peut_rejoindre_groupe()
{
    $user = Utilisateur::factory()->create();
    $groupe = Groupe::factory()->create();
    
    $this->actingAs($user)->post(route('groupes.join', $groupe))
        ->assertRedirect();
    
    $this->assertTrue($groupe->utilisateurs()->where('utilisateur_id', $user->id)->exists());
}

public function test_admin_ne_peut_pas_quitter_groupe()
{
    $admin = Utilisateur::factory()->create();
    $groupe = Groupe::factory()->create(['admin_id' => $admin->id]);
    
    $this->actingAs($admin)->post(route('groupes.leave', $groupe))
        ->assertSessionHas('error');
}
```

## 🔐 Sécurité

### Validations CSRF
✅ Tous les forms POST utilisent `@csrf`

### Vérifications d'authentification
```php
// Dans tous les contrôleurs:
public function store(): RedirectResponse
{
    $user = auth()->user();  // Garantit l'authentification
    // ...
}
```

### Vérifications de propriété
```php
// PublicationPartageController
public function destroy(Partage $partage): RedirectResponse
{
    if ($partage->utilisateur_id !== auth()->id() && !auth()->user()->estAdmin()) {
        return redirect()->back()->with('error', 'Non autorisé');
    }
    // ...
}
```

### Rate Limiting (Optionnel)
```php
// routes/web.php
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::post('/publications/{publication}/partages', [PublicationPartageController::class, 'store']);
    Route::post('/groupes/{groupe}/join', [GroupeMembreController::class, 'join']);
});
```

## 📈 Performance

### Optimisation des Requêtes

```php
// ❌ Mauvais - N+1 query
foreach ($publications as $publication) {
    $count = $publication->partages()->count();  // Query à chaque itération
}

// ✅ Bon - Preload
$publications = Publication::with('partages')->get();
foreach ($publications as $publication) {
    $count = $publication->partages->count();  // Données en mémoire
}
```

### Indexes de Base de Données

```sql
-- Ajouté automatiquement par migration:
CREATE UNIQUE INDEX partages_utilisateur_publication 
ON partages(utilisateur_id, publication_id);

CREATE INDEX groupe_utilisateurs_utilisateur_id 
ON groupe_utilisateurs(utilisateur_id);

CREATE INDEX notifications_utilisateur_id 
ON notifications(utilisateur_id);

CREATE INDEX notifications_read_at 
ON notifications(read_at);
```

## 🔄 Flux de Données

### Flux 1: Partage de Publication

```
User Action: Click "Partager" button
       ↓
JavaScript: submitForm() with @csrf token
       ↓
Route: POST /publications/{id}/partages
       ↓
Controller: PublicationPartageController@store
       ↓
Model: Partage::create() OR delete()
       ↓
Model: Notification::envoyer() for author
       ↓
Response: redirect()->back()->with('success')
       ↓
Blade: Refresco con contador actualizado
```

### Flux 2: Rejoindre Groupe

```
User Action: Click "Rejoindre" button
       ↓
JavaScript: submitForm()
       ↓
Route: POST /groupes/{id}/join
       ↓
Controller: GroupeMembreController@join
       ↓
Model: $groupe->utilisateurs()->attach($user)
       ↓
Model: Notification::envoyer() for admin
       ↓
Response: redirect()->back()->with('success')
```

## 📚 Dépendances

```json
{
  "require": {
    "laravel/framework": "^12.0",
    "php": "^8.2"
  }
}
```

### Aucune dépendance externe ajoutée!

Toutes les fonctionnalités utilisent:
- Laravel Eloquent ORM natif
- Blade templating natif
- PHP native
- SQLite (ou MySQL, PostgreSQL)

## 🚀 Déploiement

### Checklist de Déploiement

- [ ] Migration exécutée: `php artisan migrate`
- [ ] Cache des routes: `php artisan route:cache`
- [ ] Environnement production: `APP_ENV=production`
- [ ] Clé d'application: `php artisan key:generate`
- [ ] Permissions des fichiers: `storage/` writable
- [ ] Sessions configurées dans `.env`

### Étapes de Déploiement

```bash
# 1. Clone et dépendances
git pull origin main
composer install --optimize-autoloader --no-dev

# 2. Migrations
php artisan migrate --force

# 3. Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Permissions
chmod -R 755 storage bootstrap/cache

# 5. Restart (si applicable)
php artisan queue:restart
```

## 📊 Monitoring

### Queries à Monitorer

```sql
-- Publications les plus partagées
SELECT publication_id, COUNT(*) as shares
FROM partages
GROUP BY publication_id
ORDER BY shares DESC
LIMIT 10;

-- Groupes les plus populaires
SELECT groupe_id, COUNT(*) as members
FROM groupe_utilisateurs
GROUP BY groupe_id
ORDER BY members DESC;

-- Utilisateurs plus actifs
SELECT utilisateur_id, 
  (SELECT COUNT(*) FROM partages WHERE utilisateur_id = u.id) as shares,
  (SELECT COUNT(*) FROM groupe_utilisateurs WHERE utilisateur_id = u.id) as groups
FROM utilisateurs u
ORDER BY shares DESC;
```

---

**Documentation mise à jour:** 27 Décembre 2025
