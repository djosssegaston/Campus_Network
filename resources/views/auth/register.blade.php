@extends('layouts.auth')

@section('title', 'Inscription - Campus Network')

@section('content')
<x-auth-card 
    title="Créer un compte" 
    subtitle="Rejoignez Campus Network"
    gradientFrom="from-blue-600"
    gradientTo="to-blue-700"
    :footer="true"
    footerText="Déjà inscrit? "
    footerLink="{{ route('login') }}"
    footerLinkText="Se connecter"
>
    @if ($errors->any())
        <x-alert type="error" title="Erreurs d'inscription" class="mb-6">
            <ul class="list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <x-form-input
            name="nom"
            type="text"
            label="Nom complet"
            placeholder="Jean Dupont"
            required
            autofocus
        />

        <x-form-input
            name="email"
            type="email"
            label="Adresse Email"
            placeholder="vous@example.com"
            required
        />

        <x-form-input
            name="password"
            type="password"
            label="Mot de Passe (minimum 8 caractères)"
            placeholder="••••••••"
            required
        />

        <x-form-input
            name="password_confirmation"
            type="password"
            label="Confirmer le Mot de Passe"
            placeholder="••••••••"
            required
        />

        <div class="flex items-start">
            <input 
                id="terms" 
                type="checkbox" 
                name="terms"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer mt-1"
                required
            >
            <label for="terms" class="ml-2 block text-xs text-gray-700 cursor-pointer">
                J'accepte les <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">conditions d'utilisation</a>
            </label>
        </div>

        <x-button variant="primary" type="submit" class="w-full">
            S'inscrire
        </x-button>
    </form>
</x-auth-card>
@endsection
