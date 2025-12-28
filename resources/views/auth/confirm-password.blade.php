@extends('layouts.auth')

@section('title', 'Confirmer le Mot de Passe - Campus Network')

@section('content')
<x-auth-card 
    title="Confirmer votre mot de passe" 
    subtitle="Ceci est une zone sécurisée"
    gradientFrom="from-blue-600"
    gradientTo="to-blue-700"
    :footer="true"
    footerText="Retourner à "
    footerLink="{{ route('dashboard') }}"
    footerLinkText="l'accueil"
>
    <p class="text-sm text-gray-600 mb-6">
        Veuillez confirmer votre mot de passe pour continuer l'accès à cette zone sécurisée.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <x-form-input
            name="password"
            type="password"
            label="Mot de Passe"
            placeholder="••••••••"
            required
            autofocus
        />

        <x-button variant="primary" type="submit" class="w-full">
            Confirmer
        </x-button>
    </form>
</x-auth-card>
@endsection
