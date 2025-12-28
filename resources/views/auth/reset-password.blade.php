@extends('layouts.auth')

@section('title', 'Réinitialiser le Mot de Passe - Campus Network')

@section('content')
<x-auth-card 
    title="Réinitialiser votre mot de passe" 
    subtitle="Créez un nouveau mot de passe sécurisé"
    gradientFrom="from-blue-600"
    gradientTo="to-blue-700"
    :footer="true"
    footerText="Vous vous souvenez? "
    footerLink="{{ route('login') }}"
    footerLinkText="Se connecter"
>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-form-input
            name="email"
            type="email"
            label="Adresse Email"
            placeholder="vous@example.com"
            :value="old('email', $request->email)"
            required
            autofocus
        />

        <x-form-input
            name="password"
            type="password"
            label="Nouveau Mot de Passe (minimum 8 caractères)"
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

        <x-button variant="primary" type="submit" class="w-full">
            Réinitialiser le Mot de Passe
        </x-button>
    </form>
</x-auth-card>
@endsection
