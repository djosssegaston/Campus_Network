# 🎨 GUIDE DE DESIGN UNIFIÉ - CAMPUS NETWORK

**Date:** 25 Décembre 2025  
**Status:** ✅ IMPLÉMENTÉ

---

## 📋 ARCHITECTURE DU DESIGN

### **Hiérarchie des Layouts**

```
layouts/main.blade.php (Master Layout)
├─ components/navigation.blade.php
├─ @yield('content')
└─ components/footer.blade.php

layouts/auth.blade.php (Authentication Layout)
├─ Logo & Branding
├─ @yield('content')
└─ Background Animation
```

---

## 🎨 SYSTÈME DE COULEURS

### **Palette Primaire**
- **Bleu Principal:** `#3b82f6` (Blue-600)
- **Bleu Gradient:** From `#2563eb` to `#1d4ed8` (Blue-600 → Blue-700)
- **Gris Neutre:** `#6b7280` (Gray-500)
- **Blanc:** `#ffffff`

### **Couleurs Secondaires**
- **Succès:** `#10b981` (Green)
- **Erreur:** `#ef4444` (Red)
- **Avertissement:** `#f59e0b` (Amber)
- **Info:** `#3b82f6` (Blue)

### **Arrière-plans**
- **Clair:** `#f9fafb` (Gray-50)
- **Moyen:** `#f3f4f6` (Gray-100)
- **Foncé:** `#111827` (Gray-900)

---

## 🧩 COMPOSANTS RÉUTILISABLES

### **1. Auth Card**
**Fichier:** `components/auth-card.blade.php`
**Utilisation:**
```blade
<x-auth-card 
    title="Connexion" 
    subtitle="Accédez à votre compte"
    gradientFrom="from-blue-600"
    gradientTo="to-blue-700"
    :footer="true"
    footerText="Pas de compte? "
    footerLink="{{ route('register') }}"
    footerLinkText="Créer un compte"
>
    <!-- Form content -->
</x-auth-card>
```

### **2. Form Input**
**Fichier:** `components/form-input.blade.php`
**Utilisation:**
```blade
<x-form-input
    name="email"
    type="email"
    label="Email"
    placeholder="votre@email.com"
    required
/>
```

### **3. Form Textarea**
**Fichier:** `components/form-textarea.blade.php`
**Utilisation:**
```blade
<x-form-textarea
    name="message"
    label="Message"
    rows="4"
    required
/>
```

### **4. Button**
**Fichier:** `components/button.blade.php`
**Variantes:** `primary`, `secondary`, `danger`, `success`, `outline`
**Utilisation:**
```blade
<x-button variant="primary" type="submit">
    Envoyer
</x-button>
```

### **5. Alert**
**Fichier:** `components/alert.blade.php`
**Types:** `success`, `error`, `warning`, `info`
**Utilisation:**
```blade
<x-alert type="error" title="Erreur">
    Une erreur s'est produite
</x-alert>
```

### **6. Navigation**
**Fichier:** `components/navigation.blade.php`
- Header sticky avec logo
- Menu de navigation responsive
- Profil utilisateur avec dropdown
- Notifications

### **7. Footer**
**Fichier:** `components/footer.blade.php`
- Liens rapides
- Ressources
- Mentions légales
- Réseaux sociaux

---

## 📱 RESPONSIVE DESIGN

### **Breakpoints (Tailwind)**
- **Mobile:** < 640px (xs)
- **Tablet:** 640px - 1024px (sm, md, lg)
- **Desktop:** > 1024px (xl, 2xl)

### **Classes Responsive**
```blade
<!-- Mobile first -->
<div class="px-4 sm:px-6 lg:px-8">
    <!-- 16px padding mobile, 24px tablet, 32px desktop -->
</div>

<!-- Hidden au desktop -->
<button class="md:hidden">Menu</button>

<!-- Afficher grid au desktop seulement -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
```

---

## 🎯 TYPOGRAPHIE

### **Fonts**
**Font:** Inter 400, 500, 600, 700
**CDN:** fonts.bunny.net

### **Hiérarchie**
```
h1: text-4xl font-bold (Titre page)
h2: text-3xl font-bold (Section titre)
h3: text-2xl font-bold (Sous-titre)
p:  text-base (Texte normal)
label: text-sm font-medium (Labels)
button: text-sm font-semibold (Boutons)
```

---

## 🌟 EFFETS & ANIMATIONS

### **Transitions**
```css
transition-all 0.3s ease
transition-colors
transition-opacity
```

### **Hover Effects**
```
Buttons: 
  - Shadow increase
  - Background color shift
  - Scale on active

Links:
  - Text color change
  - Underline appearance

Cards:
  - Shadow elevation
  - Scale slight
```

### **Focus States**
```css
focus:outline-none
focus:ring-2 focus:ring-offset-2
focus:ring-blue-500
```

---

## 📏 SPACING SYSTEM

**Basé sur Tailwind's scale:**
```
px-1 = 4px
px-2 = 8px
px-3 = 12px
px-4 = 16px
px-6 = 24px
px-8 = 32px
...
```

**Usage:**
- **Container:** `max-w-7xl mx-auto`
- **Padding interno:** `px-4 sm:px-6 lg:px-8`
- **Spacing vertical:** `py-8 sm:py-12 lg:py-16`

---

## 🔐 États de Formulaire

### **Input Normal**
```css
border-2 border-gray-300
focus:border-blue-500
focus:ring-2 focus:ring-blue-500
```

### **Input Error**
```css
border-2 border-red-500
bg-red-50
focus:border-red-500
focus:ring-2 focus:ring-red-500
```

### **Input Disabled**
```css
bg-gray-100
text-gray-500
cursor-not-allowed
opacity-50
```

---

## 🎭 DARK MODE (Future)

Pour implémenter le mode sombre:
```blade
<!-- Dans le layout principal -->
<html class="light" x-data="{ dark: false }">
    <body :class="{ 'dark': dark }">
```

---

## 🚀 PAGES IMPLÉMENTÉES AVEC DESIGN UNIFIÉ

- ✅ `auth/login.blade.php` - Connexion
- ✅ `auth/register.blade.php` - Enregistrement
- ⏳ `dashboard.blade.php` - À refactoriser
- ⏳ `feed.blade.php` - À refactoriser
- ⏳ `groupes/*` - À refactoriser
- ⏳ `messages/*` - À refactoriser
- ⏳ `profile/*` - À refactoriser

---

## 📝 GUIDE D'UTILISATION DES COMPOSANTS

### **Template Standard pour Pages**
```blade
@extends('layouts.main')

@section('title', 'Page Title - Campus Network')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Titre</h1>
        <p class="text-gray-600 mt-2">Sous-titre</p>
    </div>
    
    <!-- Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Cards, Forms, etc -->
    </div>
</div>
@endsection
```

### **Template pour Formulaires**
```blade
@extends('layouts.auth')

@section('title', 'Form Title')

@section('content')
<x-auth-card title="Titre" subtitle="Sous-titre">
    <form method="POST" class="space-y-5">
        @csrf
        
        <x-form-input name="field1" label="Champ 1" required />
        <x-form-textarea name="message" label="Message" required />
        
        @if ($errors->any())
            <x-alert type="error">
                Vérifiez vos données
            </x-alert>
        @endif
        
        <x-button variant="primary">
            Envoyer
        </x-button>
    </form>
</x-auth-card>
@endsection
```

---

## ✨ CHECKLIST DE CONFORMITÉ

- ✅ Layout master unifié (`layouts/main.blade.php`)
- ✅ Navigation responsive avec dropdown
- ✅ Footer complet avec liens
- ✅ 7 composants réutilisables
- ✅ Système de couleurs cohérent
- ✅ Typography standardisée
- ✅ Spacing system uniformisé
- ✅ States de formulaire (normal, error, disabled)
- ✅ Responsive design (mobile-first)
- ✅ Transitions & animations smoothes
- ✅ Accessibility (focus states, labels)

---

## 🔄 PROCHAINES ÉTAPES

1. **Refactoriser les pages existantes** avec le nouveau design
2. **Ajouter dark mode** (optionnel)
3. **Implémenter animations** plus sophistiquées
4. **Tester** sur tous les appareils
5. **Documenter** pour l'équipe

---

## 📞 SUPPORT

Pour ajouter un nouveau composant:
1. Créer `resources/views/components/name.blade.php`
2. Utiliser: `<x-name prop="value" />`
3. Respecter la palette de couleurs
4. Assurer la responsivité
5. Documenter dans ce guide
