# 🧩 COMPOSANTS BLADE RÉUTILISABLES (Bonus)

Ces fichiers sont optionnels mais recommandés pour un code plus maintenable.

---

## 1️⃣ `resources/views/components/button.blade.php`

```blade
@props(['type' => 'button', 'variant' => 'primary'])

@php
$classes = 'px-4 py-2 rounded-lg font-medium transition focus:outline-none';

$variantClasses = match($variant) {
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500',
    'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-2 focus:ring-gray-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-500',
    'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-2 focus:ring-green-500',
    default => 'bg-blue-600 text-white hover:bg-blue-700'
};
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes . ' ' . $variantClasses]) }}>
    {{ $slot }}
</button>
```

**Utilisation:**
```blade
<x-button>Enregistrer</x-button>
<x-button variant="danger">Supprimer</x-button>
<x-button variant="secondary" type="submit">Annuler</x-button>
```

---

## 2️⃣ `resources/views/components/input.blade.php`

```blade
@props(['label', 'type' => 'text', 'error' => null])

<div>
    @if($label)
        <label {{ $attributes->only('for') }} class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        {{ $attributes->except('label', 'error')->merge(['class' => 'w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent ' . ($error ? 'border-red-500' : 'border-gray-300')]) }}
    >
    
    @if($error)
        <p class="text-red-600 text-sm mt-1">{{ $error }}</p>
    @endif
</div>
```

**Utilisation:**
```blade
<x-input label="Email" type="email" name="email" />
<x-input label="Mot de Passe" type="password" name="password" :error="$errors->first('password')" />
```

---

## 3️⃣ `resources/views/components/card.blade.php`

```blade
@props(['title' => null, 'subtitle' => null])

<div class="bg-white rounded-lg shadow {{ $attributes->get('class') }}">
    @if($title || isset($header))
        <div class="border-b p-6">
            @if($title)
                <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-sm text-gray-600">{{ $subtitle }}</p>
                @endif
            @else
                {{ $header ?? '' }}
            @endif
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="border-t p-6">
            {{ $footer }}
        </div>
    @endif
</div>
```

**Utilisation:**
```blade
<x-card title="Profil Utilisateur">
    <p>Contenu du profil</p>
</x-card>

<x-card>
    <x-slot name="header">
        <h2>Titre personnalisé</h2>
    </x-slot>
    Contenu...
    <x-slot name="footer">
        <button>Enregistrer</button>
    </x-slot>
</x-card>
```

---

## 4️⃣ `resources/views/components/alert.blade.php`

```blade
@props(['type' => 'info'])

@php
$colors = [
    'success' => 'bg-green-100 border-green-400 text-green-700',
    'error' => 'bg-red-100 border-red-400 text-red-700',
    'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    'info' => 'bg-blue-100 border-blue-400 text-blue-700',
];
@endphp

<div class="p-4 border rounded-lg {{ $colors[$type] ?? $colors['info'] }}">
    {{ $slot }}
</div>
```

**Utilisation:**
```blade
<x-alert type="success">Publication créée avec succès!</x-alert>
<x-alert type="error">Une erreur est survenue.</x-alert>
<x-alert type="warning">Attention: Donnée importante</x-alert>
```

---

## 5️⃣ `resources/views/components/modal.blade.php`

```blade
@props(['id'])

<div 
    x-data="{ open: false }" 
    x-show="open" 
    x-transition
    style="display: none"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    id="{{ $id }}"
>
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6" @click.away="open = false">
        <div class="flex justify-between items-center mb-4">
            {{ $header ?? '' }}
            <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        {{ $slot }}
        
        @if(isset($footer))
            <div class="border-t mt-6 pt-6 flex gap-4">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
```

**Utilisation:**
```blade
<x-modal id="confirm-modal">
    <x-slot name="header">
        <h2 class="text-lg font-bold">Confirmer?</h2>
    </x-slot>
    Êtes-vous sûr de vouloir continuer?
    <x-slot name="footer">
        <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded">Annuler</button>
        <button @click="open = false" class="px-4 py-2 bg-blue-600 text-white rounded">Confirmer</button>
    </x-slot>
</x-modal>

<!-- Ouvrir le modal -->
<button @click="document.getElementById('confirm-modal').querySelector('div[x-data]').__x.$data.open = true">
    Ouvrir Modal
</button>
```

---

## 6️⃣ `resources/views/components/form-group.blade.php`

```blade
@props(['label', 'error' => null])

<div class="mb-4">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
        </label>
    @endif
    
    <div>
        {{ $slot }}
    </div>
    
    @if($error)
        <p class="text-red-600 text-sm mt-1">{{ $error }}</p>
    @endif
</div>
```

**Utilisation:**
```blade
<x-form-group label="Nom Complet" :error="$errors->first('name')">
    <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg">
</x-form-group>
```

---

## 7️⃣ `resources/views/components/pagination.blade.php`

```blade
@props(['items'])

@if($items->lastPage() > 1)
    <div class="flex justify-center gap-2 mt-8">
        {{-- Bouton Précédent --}}
        @if($items->onFirstPage())
            <button disabled class="px-4 py-2 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed">
                ← Précédent
            </button>
        @else
            <a href="{{ $items->previousPageUrl() }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                ← Précédent
            </a>
        @endif
        
        {{-- Pages --}}
        @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
            @if($page == $items->currentPage())
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold">
                    {{ $page }}
                </button>
            @else
                <a href="{{ $url }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    {{ $page }}
                </a>
            @endif
        @endforeach
        
        {{-- Bouton Suivant --}}
        @if($items->hasMorePages())
            <a href="{{ $items->nextPageUrl() }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Suivant →
            </a>
        @else
            <button disabled class="px-4 py-2 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed">
                Suivant →
            </button>
        @endif
    </div>
@endif
```

**Utilisation:**
```blade
<x-pagination :items="$publications" />
```

---

## 8️⃣ `resources/views/includes/errors.blade.php`

```blade
@if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <h4 class="font-bold">Erreurs de validation:</h4>
        <ul class="list-disc pl-5 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

**Utilisation:**
```blade
@include('includes.errors')
```

---

## 9️⃣ `resources/views/includes/navigation.blade.php`

```blade
<!-- Navigation réutilisable -->
<nav class="border-b border-gray-100 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex h-16 justify-between items-center">
            <!-- Logo -->
            <a href="/" class="text-xl font-bold text-blue-600">Campus Network</a>
            
            <!-- Menu -->
            <div class="hidden md:flex gap-8">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                <a href="{{ route('feed') }}" class="text-gray-600 hover:text-gray-900">Feed</a>
                <a href="{{ route('groupes.index') }}" class="text-gray-600 hover:text-gray-900">Groupes</a>
            </div>
            
            <!-- Profil -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-600 hover:text-gray-900">
                    {{ auth()->user()->name }} ▼
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded shadow-lg">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button class="w-full text-left px-4 py-2 hover:bg-gray-100">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
```

**Utilisation:**
```blade
@include('includes.navigation')

<!-- Contenu page -->
```

---

## 🔟 `resources/views/includes/footer.blade.php`

```blade
<footer class="bg-gray-800 text-white mt-12">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <div>
                <h3 class="font-bold mb-2">Campus Network</h3>
                <p class="text-gray-400 text-sm">Plateforme communautaire pour étudiants</p>
            </div>
            <div>
                <h3 class="font-bold mb-2">Liens Rapides</h3>
                <ul class="text-gray-400 text-sm space-y-1">
                    <li><a href="#" class="hover:text-white">Accueil</a></li>
                    <li><a href="#" class="hover:text-white">Groupes</a></li>
                    <li><a href="#" class="hover:text-white">Messages</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-2">Contact</h3>
                <p class="text-gray-400 text-sm">contact@campus-network.com</p>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; 2024 Campus Network. Tous droits réservés.</p>
        </div>
    </div>
</footer>
```

---

## 📝 Installation et Utilisation

### Créer un composant Blade
1. Créer fichier: `resources/views/components/mon-composant.blade.php`
2. Utiliser: `<x-mon-composant />`

### Propriétés et Slots
```blade
<!-- Définir dans le composant -->
@props(['title', 'color' => 'blue'])
<x-slot name="header">...</x-slot>
<x-slot name="footer">...</x-slot>

<!-- Utiliser -->
<x-mon-composant title="Titre" color="red">
    <x-slot name="header">En-tête</x-slot>
    Contenu
    <x-slot name="footer">Pied de page</x-slot>
</x-mon-composant>
```

---

## ✨ Avantages des Composants Blade

✅ Code réutilisable
✅ DRY (Don't Repeat Yourself)
✅ Facile à maintenir
✅ Consistent design
✅ Temps développement réduit

---

**Bonus: Composants Blade Réutilisables**
Utilisez-les pour accélérer le développement! 🚀
