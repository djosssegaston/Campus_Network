# 🚀 PLAN D'IMPLÉMENTATION - COMPLÉTION CAMPUS NETWORK

**Date**: 27 Décembre 2025  
**Objectif**: Compléter les 8 fonctionnalités incomplètes (actuelles: 82% → Cible: 95%+)  
**Approche**: Augmentations ciblées, respecter architecture existante, AUCUNE refactorisation

---

## 📊 PRIORITÉS D'IMPLÉMENTATION

### 🔴 CRITIQUE (Semaine 1)

**Aucune** - Le système est stable et fonctionnel

### 🟠 HAUTE (Semaine 1-2)

1. **[1] Notifications temps réel** - 60% → 95%
2. **[2] Signalements/modération** - 50% → 90%
3. **[3] Tableau admin** - 60% → 85%

### 🟡 MOYENNE (Semaine 2-3)

4. **[4] Confidentialité** - 80% → 95%
5. **[5] Audit logs** - 40% → 80%
6. **[6] Export RGPD** - 85% → 95%

### 🟢 FAIBLE (Semaine 3+)

7. **[7] Recherche** - 90% → 95%

---

---

## [1] 🔥 NOTIFICATIONS TEMPS RÉEL (60% → 95%)

**Problème**: Notifications créées manuellement, jamais déclenchées par actions utilisateur

### 1.1 Architecture à ajouter

```
Events/
├── CommentaireCreated.php      ⬅️ NEW
├── ReactionCreated.php         ⬅️ NEW
├── MessageSent.php             ⬅️ NEW
└── UserMentionned.php          ⬅️ NEW

Listeners/
├── SendCommentaireNotification.php     ⬅️ NEW
├── SendReactionNotification.php        ⬅️ NEW
├── SendMessageNotification.php         ⬅️ NEW
└── SendMentionNotification.php         ⬅️ NEW
```

### 1.2 Implémentation détaillée

#### Étape 1: Créer Events

**File**: `app/Events/CommentaireCreated.php`
```php
<?php
namespace App\Events;

use App\Models\Commentaire;
use Illuminate\Foundation\Events\Dispatchable;

class CommentaireCreated {
    use Dispatchable;
    
    public function __construct(public Commentaire $commentaire) {}
}
```

**File**: `app/Events/ReactionCreated.php`
```php
<?php
namespace App\Events;

use App\Models\Reaction;
use Illuminate\Foundation\Events\Dispatchable;

class ReactionCreated {
    use Dispatchable;
    
    public function __construct(public Reaction $reaction) {}
}
```

**File**: `app/Events/MessageSent.php`
```php
<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Foundation\Events\Dispatchable;

class MessageSent {
    use Dispatchable;
    
    public function __construct(public Message $message) {}
}
```

#### Étape 2: Créer Listeners

**File**: `app/Listeners/SendCommentaireNotification.php`
```php
<?php
namespace App\Listeners;

use App\Events\CommentaireCreated;
use App\Models\Notification;

class SendCommentaireNotification {
    public function handle(CommentaireCreated $event) {
        $commentaire = $event->commentaire;
        $publication = $commentaire->publication;
        
        // Notifier auteur publication
        if ($publication->utilisateur_id !== $commentaire->utilisateur_id) {
            Notification::create([
                'utilisateur_id' => $publication->utilisateur_id,
                'type' => 'commentaire',
                'donnees' => [
                    'publication_id' => $publication->id,
                    'commentaire_id' => $commentaire->id,
                    'user_name' => $commentaire->utilisateur->nom,
                ],
            ]);
        }
        
        // Notifier autres commentateurs
        $otherCommenters = $publication->commentaires()
            ->where('utilisateur_id', '!=', $commentaire->utilisateur_id)
            ->distinct('utilisateur_id')
            ->pluck('utilisateur_id');
            
        foreach ($otherCommenters as $userId) {
            Notification::create([
                'utilisateur_id' => $userId,
                'type' => 'commentaire_reponse',
                'donnees' => [
                    'publication_id' => $publication->id,
                    'user_name' => $commentaire->utilisateur->nom,
                ],
            ]);
        }
    }
}
```

**File**: `app/Listeners/SendReactionNotification.php`
```php
<?php
namespace App\Listeners;

use App\Events\ReactionCreated;
use App\Models\Notification;

class SendReactionNotification {
    public function handle(ReactionCreated $event) {
        $reaction = $event->reaction;
        
        // Déterminer propriétaire du contenu réagi
        if ($reaction->reactable_type === 'App\Models\Publication') {
            $publication = $reaction->reactable;
            $owner = $publication->utilisateur;
        } elseif ($reaction->reactable_type === 'App\Models\Commentaire') {
            $commentaire = $reaction->reactable;
            $owner = $commentaire->utilisateur;
        }
        
        // Notifier propriétaire si pas l'auteur
        if ($owner && $owner->id !== $reaction->utilisateur_id) {
            Notification::create([
                'utilisateur_id' => $owner->id,
                'type' => 'reaction',
                'donnees' => [
                    'reaction_type' => $reaction->type,
                    'user_name' => $reaction->utilisateur->nom,
                    'content_type' => $reaction->reactable_type,
                ],
            ]);
        }
    }
}
```

**File**: `app/Listeners/SendMessageNotification.php`
```php
<?php
namespace App\Listeners;

use App\Events\MessageSent;
use App\Models\Notification;

class SendMessageNotification {
    public function handle(MessageSent $event) {
        $message = $event->message;
        $conversation = $message->conversation;
        
        // Notifier tous les participants sauf expéditeur
        $participants = $conversation->utilisateurs()
            ->where('utilisateur_id', '!=', $message->expediteur_id)
            ->pluck('utilisateur_id');
            
        foreach ($participants as $userId) {
            Notification::create([
                'utilisateur_id' => $userId,
                'type' => 'message',
                'donnees' => [
                    'conversation_id' => $conversation->id,
                    'sender_name' => $message->expediteur->nom,
                    'preview' => substr($message->contenu, 0, 50),
                ],
            ]);
        }
    }
}
```

#### Étape 3: Enregistrer Events dans EventServiceProvider

**File**: `app/Providers/EventServiceProvider.php`
```php
protected $listen = [
    // ... autres events
    \App\Events\CommentaireCreated::class => [
        \App\Listeners\SendCommentaireNotification::class,
    ],
    \App\Events\ReactionCreated::class => [
        \App\Listeners\SendReactionNotification::class,
    ],
    \App\Events\MessageSent::class => [
        \App\Listeners\SendMessageNotification::class,
    ],
];
```

#### Étape 4: Dispatcher Events depuis Controllers

**File**: `app/Http/Controllers/Api/CommentaireController.php` (dans `store()`)
```php
// Avant: return response()->json($commentaire);
// Après:
event(new \App\Events\CommentaireCreated($commentaire));
return response()->json($commentaire);
```

**File**: `app/Http/Controllers/Api/ReactionController.php` (dans `store()`)
```php
// Ajouter
event(new \App\Events\ReactionCreated($reaction));
```

**File**: `app/Http/Controllers/Api/MessageController.php` (dans `store()`)
```php
// Ajouter
event(new \App\Events\MessageSent($message));
```

### 1.3 Effort estimé
- **Création fichiers**: 4 Events + 4 Listeners = 8 fichiers
- **Modification controllers**: 3 contrôleurs
- **Modification EventServiceProvider**: 1 changement
- **Temps total**: 1-2 heures
- **Risque**: Très bas (pas d'impact existant)

### 1.4 Vérification
```bash
# Test créer commentaire → vérifier notification créée en BD
php artisan tinker
> \App\Models\Notification::latest()->first();
```

---

## [2] 🔥 SIGNALEMENTS/MODÉRATION (50% → 90%)

**Problème**: Table existe, mais pas de flux pour créer/traiter signalements

### 2.1 Architecture à ajouter

```
Controllers/
├── Api/SignalementController.php    ⬅️ NEW
└── SignalementViewController.php    ⬅️ NEW (optionnel)

Models/
└── Signalement.php                 ⬅️ Vérifier existant

Views/
└── signalements/
    ├── modal.blade.php             ⬅️ NEW (formulaire)
    └── admin/show.blade.php        ⬅️ NEW (détails + actions)
```

### 2.2 Implémentation détaillée

#### Étape 1: Vérifier/améliorer Model Signalement

**File**: `app/Models/Signalement.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signalement extends Model {
    use SoftDeletes;
    
    protected $fillable = [
        'utilisateur_id',
        'signalee_id',
        'model_type',
        'model_id',
        'raison',
        'description',
        'statut', // pending, reviewed, resolved, rejected
        'reponse_moderateur',
        'resolved_at',
        'resolved_by',
    ];
    
    protected $casts = [
        'resolved_at' => 'datetime',
    ];
    
    // Relations
    public function signaleur() {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
    
    public function signale() {
        return $this->belongsTo(Utilisateur::class, 'signalee_id');
    }
    
    public function moderateur() {
        return $this->belongsTo(Utilisateur::class, 'resolved_by');
    }
    
    public function content() {
        return $this->morphTo();
    }
    
    // Scopes
    public function scopePending($query) {
        return $query->where('statut', 'pending');
    }
    
    public function scopeResolved($query) {
        return $query->where('statut', '!=', 'pending');
    }
}
```

#### Étape 2: Créer SignalementController API

**File**: `app/Http/Controllers/Api/SignalementController.php`
```php
<?php
namespace App\Http\Controllers\Api;

use App\Models\Signalement;
use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class SignalementController {
    
    // Créer signalement (utilisateur publique)
    public function store(Request $request) {
        $validated = $request->validate([
            'model_type' => 'required|in:App\Models\Publication,App\Models\Utilisateur,App\Models\Commentaire',
            'model_id' => 'required|integer',
            'raison' => 'required|in:spam,offense,contenu_adult,autre',
            'description' => 'nullable|string|max:500',
        ]);
        
        $signalement = Signalement::create([
            'utilisateur_id' => auth()->id(),
            'model_type' => $validated['model_type'],
            'model_id' => $validated['model_id'],
            'raison' => $validated['raison'],
            'description' => $validated['description'],
            'statut' => 'pending',
        ]);
        
        return response()->json([
            'message' => 'Signalement envoyé aux modérateurs',
            'signalement' => $signalement,
        ], 201);
    }
    
    // Admin: lister signalements en attente
    public function pending() {
        $signalements = Signalement::pending()
            ->with(['signaleur', 'moderateur'])
            ->paginate(15);
            
        return response()->json($signalements);
    }
    
    // Admin: détail signalement
    public function show($id) {
        $signalement = Signalement::findOrFail($id);
        
        return response()->json($signalement->load([
            'signaleur',
            'moderateur',
            'content',
        ]));
    }
    
    // Admin: valider signalement + action
    public function resolve(Request $request, $id) {
        $signalement = Signalement::findOrFail($id);
        
        $validated = $request->validate([
            'action' => 'required|in:approuver,rejeter,archiver',
            'reponse' => 'nullable|string|max:500',
        ]);
        
        $signalement->update([
            'statut' => $validated['action'] === 'approuver' ? 'resolved' : 'rejected',
            'reponse_moderateur' => $validated['reponse'],
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);
        
        // Si approuvé: supprimer le contenu signalé
        if ($validated['action'] === 'approuver') {
            $content = $signalement->content;
            if ($content) {
                $content->delete(); // Soft delete
                
                // Notifier auteur
                Notification::create([
                    'utilisateur_id' => $content->utilisateur_id,
                    'type' => 'contenu_supprime',
                    'donnees' => [
                        'raison' => $signalement->raison,
                        'reponse' => $validated['reponse'],
                    ],
                ]);
            }
        }
        
        return response()->json([
            'message' => 'Signalement traité',
            'signalement' => $signalement,
        ]);
    }
}
```

#### Étape 3: Créer routes signalements

**File**: `routes/api.php` (ajouter)
```php
// Routes signalements (utilisateur)
Route::post('/signalements', [Api\SignalementController::class, 'store'])
    ->middleware('auth:sanctum');

// Routes signalements (admin)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/signalements/pending', [Api\SignalementController::class, 'pending']);
    Route::get('/signalements/{id}', [Api\SignalementController::class, 'show']);
    Route::post('/signalements/{id}/resolve', [Api\SignalementController::class, 'resolve']);
});
```

#### Étape 4: Créer views

**File**: `resources/views/signalements/modal.blade.php` (NEW)
```blade
<div id="signalementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h2 class="text-lg font-bold mb-4">Signaler ce contenu</h2>
        
        <form id="signalementForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2">Raison</label>
                <select name="raison" required class="w-full border rounded p-2">
                    <option value="">-- Sélectionner --</option>
                    <option value="spam">Spam</option>
                    <option value="offense">Contenu offensant</option>
                    <option value="contenu_adult">Contenu adulte</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2">Description (optionnel)</label>
                <textarea name="description" class="w-full border rounded p-2 h-20"></textarea>
            </div>
            
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeSignalement()" class="px-4 py-2 bg-gray-300 rounded">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Signaler</button>
            </div>
        </form>
    </div>
</div>

<script>
function openSignalement(modelType, modelId) {
    document.getElementById('signalementForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        try {
            const response = await fetch('/api/v1/signalements', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    model_type: modelType,
                    model_id: modelId,
                    raison: formData.get('raison'),
                    description: formData.get('description'),
                }),
            });
            
            if (response.ok) {
                alert('Signalement envoyé aux modérateurs');
                closeSignalement();
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    };
    
    document.getElementById('signalementModal').classList.remove('hidden');
}

function closeSignalement() {
    document.getElementById('signalementModal').classList.add('hidden');
}
</script>
```

#### Étape 5: Ajouter bouton signaler sur publications

**File**: `resources/views/feed.blade.php` (modifier partie publication)
```blade
<!-- Ajouter bouton Signaler -->
<button onclick="openSignalement('App\\Models\\Publication', {{ $publication->id }})" class="text-red-500 text-sm hover:underline">
    Signaler
</button>
```

### 2.3 Effort estimé
- **Fichiers créés**: 3 (1 Controller, 2 Views)
- **Fichiers modifiés**: 3 (Model, routes, view)
- **Temps total**: 2-3 heures
- **Risque**: Très bas

### 2.4 Vérification
```bash
# Test créer signalement
curl -X POST http://localhost:8000/api/v1/signalements \
  -H "Authorization: Bearer TOKEN" \
  -d '{"model_type":"App\Models\Publication","model_id":1,"raison":"spam"}'
```

---

## [3] 🟠 TABLEAU ADMIN (60% → 85%)

**Problème**: Dashboard basique, pas de visualisations ni de filtres avancés

### 3.1 Améliorations sans refactorisation

#### Étape 1: Améliorer AdminController API

**File**: `app/Http/Controllers/Api/AdminController.php` (ajouter methods)
```php
// Ajouter nouvelle method: stats avancées
public function advancedStats() {
    return response()->json([
        'users' => [
            'total' => Utilisateur::count(),
            'actifs_7j' => Utilisateur::where('last_login_at', '>', now()->subDays(7))->count(),
            'par_role' => Utilisateur::groupBy('role_id')
                ->selectRaw('role_id, count(*) as count')
                ->get(),
        ],
        'publications' => [
            'total' => Publication::count(),
            'cette_semaine' => Publication::where('created_at', '>', now()->subDays(7))->count(),
            'par_groupe' => Publication::groupBy('groupe_id')
                ->selectRaw('groupe_id, count(*) as count')
                ->get(),
        ],
        'engagements' => [
            'commentaires' => Commentaire::count(),
            'reactions' => Reaction::count(),
            'messages' => Message::count(),
        ],
        'moderation' => [
            'signalements_en_attente' => Signalement::pending()->count(),
            'signalements_traites_7j' => Signalement::where('resolved_at', '>', now()->subDays(7))->count(),
        ],
    ]);
}

// Ajouter filters aux existants
public function users(Request $request) {
    $query = Utilisateur::query();
    
    // Filtres optionnels
    if ($request->has('role_id')) {
        $query->where('role_id', $request->role_id);
    }
    if ($request->has('search')) {
        $query->where('nom', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
    }
    if ($request->has('created_after')) {
        $query->where('created_at', '>', $request->created_after);
    }
    
    return response()->json($query->paginate(15));
}
```

#### Étape 2: Améliorer vue dashboard

**File**: `resources/views/admin/index.blade.php` (remplacer contenu)
```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Tableau de Bord Admin</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Utilisateurs</h3>
            <p class="text-3xl font-bold mt-2" id="totalUsers">-</p>
            <p class="text-green-600 text-sm mt-2" id="activeUsers">-</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Publications</h3>
            <p class="text-3xl font-bold mt-2" id="totalPosts">-</p>
            <p class="text-green-600 text-sm mt-2" id="weekPosts">-</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Engagements</h3>
            <p class="text-3xl font-bold mt-2" id="totalEngagement">-</p>
            <p class="text-gray-600 text-sm mt-2" id="engagementBreakdown">-</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Signalements</h3>
            <p class="text-3xl font-bold text-red-600 mt-2" id="pendingReports">-</p>
            <p class="text-orange-600 text-sm mt-2">À traiter</p>
        </div>
    </div>
    
    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b flex gap-4 p-4">
            <button onclick="switchTab('users')" class="tab-button active px-4 py-2 border-b-2 border-blue-500">
                Utilisateurs
            </button>
            <button onclick="switchTab('publications')" class="tab-button px-4 py-2">
                Publications
            </button>
            <button onclick="switchTab('reports')" class="tab-button px-4 py-2">
                Signalements
            </button>
        </div>
        
        <!-- Tab Content -->
        <div id="users-tab" class="tab-content p-4">
            <div class="mb-4 flex gap-4">
                <input type="text" id="userSearch" placeholder="Chercher utilisateur..." class="flex-1 border rounded p-2">
                <select id="roleFilter" class="border rounded p-2">
                    <option value="">-- Tous les rôles --</option>
                    <option value="1">Étudiant</option>
                    <option value="2">Modérateur</option>
                    <option value="3">Admin</option>
                </select>
            </div>
            <div id="usersTable"></div>
        </div>
        
        <div id="publications-tab" class="tab-content p-4 hidden">
            <div id="publicationsTable"></div>
        </div>
        
        <div id="reports-tab" class="tab-content p-4 hidden">
            <div id="reportsTable"></div>
        </div>
    </div>
</div>

<script>
let currentTab = 'users';

function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelector(`#${tab}-tab`).classList.remove('hidden');
    document.querySelectorAll('.tab-button').forEach(el => el.classList.remove('border-blue-500'));
    event.target.classList.add('border-blue-500');
    currentTab = tab;
    loadTabData();
}

async function loadStats() {
    const response = await fetch('/api/v1/admin/stats/advanced');
    const stats = await response.json();
    
    document.getElementById('totalUsers').textContent = stats.users.total;
    document.getElementById('activeUsers').textContent = stats.users.actifs_7j + ' actifs (7j)';
    document.getElementById('totalPosts').textContent = stats.publications.total;
    document.getElementById('weekPosts').textContent = stats.publications.cette_semaine + ' cette semaine';
    document.getElementById('totalEngagement').textContent = (stats.engagements.commentaires + stats.engagements.reactions) + ' interactions';
    document.getElementById('pendingReports').textContent = stats.moderation.signalements_en_attente;
}

async function loadTabData() {
    if (currentTab === 'users') {
        const response = await fetch('/api/v1/admin/users?search=' + (document.getElementById('userSearch').value || ''));
        const users = await response.json();
        
        let html = '<table class="w-full"><thead><tr><th class="text-left p-2">Nom</th><th class="text-left p-2">Email</th><th class="text-left p-2">Rôle</th><th>Actions</th></tr></thead><tbody>';
        users.data.forEach(user => {
            html += `<tr class="border-t"><td class="p-2">${user.nom}</td><td class="p-2">${user.email}</td><td class="p-2">${user.role}</td><td class="p-2"><button onclick="deleteUser(${user.id})" class="text-red-600">Supprimer</button></td></tr>`;
        });
        html += '</tbody></table>';
        document.getElementById('usersTable').innerHTML = html;
    }
}

loadStats();
setInterval(loadStats, 30000); // Refresh stats every 30s
</script>
@endsection
```

### 3.2 Effort estimé
- **Fichiers modifiés**: 2 (Controller, View)
- **Temps total**: 1-2 heures
- **Risque**: Très bas (amélioration UI uniquement)

---

## [4] 🟡 CONFIDENTIALITÉ (80% → 95%)

**Problème**: Paramètres sauvegardés mais non appliqués

### 4.1 Ajouter middleware de vérification

**File**: `app/Http/Middleware/ApplyPrivacySettings.php` (NEW)
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApplyPrivacySettings {
    public function handle(Request $request, Closure $next) {
        // Dans FeedController, SearchController, etc.
        // Ajouter vérification avant retourner publications
        return $next($request);
    }
}
```

**File**: `app/Http/Controllers/FeedController.php` (modifier `index()`)
```php
public function index() {
    $authUser = auth()->user();
    
    // Récupérer publications visibles selon settings
    $publications = Publication::query()
        ->where(function($q) use ($authUser) {
            // Mes publications
            $q->where('utilisateur_id', $authUser->id);
            
            // Ou publications publiques
            $q->orWhere('visibilite', 'public');
            
            // Ou publications groupes où je suis
            $q->orWhere(function($q2) use ($authUser) {
                $q2->where('visibilite', 'groupe')
                   ->whereIn('groupe_id', $authUser->groupes()->pluck('id'));
            });
        })
        ->with(['utilisateur', 'commentaires' => function($q) {
            $q->with('reactions', 'utilisateur');
        }, 'reactions'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    
    return view('feed', ['publications' => $publications]);
}
```

### 4.2 Effort estimé
- **Fichiers créés**: 1
- **Fichiers modifiés**: 2-3
- **Temps total**: 1 heure
- **Risque**: Très bas

---

## [5] 🟡 AUDIT LOGS (40% → 80%)

**Problème**: Table existe mais aucun event logging

### 5.1 Créer Event Listeners pour logging

**File**: `app/Listeners/LogAction.php` (NEW)
```php
<?php
namespace App\Listeners;

use App\Models\AuditLog;

class LogAction {
    public function handle($event) {
        $user = auth()->user();
        
        // Déterminer action et modèle
        $model = null;
        $action = class_basename($event);
        
        if (property_exists($event, 'publication')) {
            $model = $event->publication;
        } elseif (property_exists($event, 'commentaire')) {
            $model = $event->commentaire;
        }
        
        AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model' => $model ? class_basename($model) : null,
            'entity_id' => $model?->id,
            'changes' => [],
            'created_at' => now(),
        ]);
    }
}
```

### 5.2 Effort estimé
- **Fichiers créés**: 1
- **Fichiers modifiés**: 2-3
- **Temps total**: 1-2 heures

---

## [6] 🟡 EXPORT RGPD (85% → 95%)

**Problème**: Jobs asynchrones à vérifier/améliorer

### 6.1 Vérifier ExportUserDataJob

**File**: `app/Jobs/ExportUserDataJob.php` (vérifier existence)
```php
// À documenter une fois localisé
```

### 6.2 Effort estimé
- **Fichiers modifiés**: 1-2
- **Temps total**: 1 heure

---

## [7] 🟢 RECHERCHE (90% → 95%)

**Problème**: Logique existe, UI basique

### 7.1 Améliorer affichage résultats

**File**: `resources/views/search/index.blade.php` (améliorer)
```blade
<!-- Ajouter styled result cards avec icônes -->
```

### 7.2 Effort estimé
- **Fichiers modifiés**: 1
- **Temps total**: 30 min

---

## 🎯 RÉSUMÉ PLAN D'ACTION

| Phase | Tâche | Fichiers | Temps |
|---|---|---|---|
| **1 - HAUTE** | Notifications | 8 new, 3 mod | 1-2h |
| **1 - HAUTE** | Signalements | 3 new, 3 mod | 2-3h |
| **1 - HAUTE** | Admin Dashboard | 2 mod | 1-2h |
| **2 - MOYENNE** | Confidentialité | 1 new, 2 mod | 1h |
| **2 - MOYENNE** | Audit logs | 1 new, 2 mod | 1-2h |
| **2 - MOYENNE** | Export | 1-2 mod | 1h |
| **3 - FAIBLE** | Recherche UI | 1 mod | 30 min |
| | **TOTAL** | | **8-12h** |

---

## ✅ PROCHAINES ÉTAPES

### Immédiate
1. ✅ Lire ce document
2. Choisir implémentation [1] Notifications (priorité plus haute)
3. Créer fichiers Events/Listeners
4. Dispatcher events depuis controllers

### Après Phase 1
- [ ] Implémenter Signalements [2]
- [ ] Améliorer Admin Dashboard [3]

### Après Phase 2
- [ ] Appliquer logique Confidentialité [4]
- [ ] Setup Audit Logging [5]

---

**FIN DU PLAN D'IMPLÉMENTATION**

Prêt à commencer? Reconfirmez qui implémente quoi.
