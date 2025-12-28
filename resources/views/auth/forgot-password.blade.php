@extends('layouts.auth')

@section('title', 'Mot de passe oublié - Campus Network')

@section('content')
<x-auth-card 
    title="Réinitialiser votre mot de passe" 
    subtitle="Entrez votre adresse email pour recevoir un lien de réinitialisation"
    gradientFrom="from-blue-600"
    gradientTo="to-blue-700"
    :footer="true"
    footerText="Vous vous souvenez? "
    footerLink="{{ route('login') }}"
    footerLinkText="Se connecter"
>
    @if (session('status'))
        <x-alert type="success" title="Email envoyé">
            {{ session('status') }}
        </x-alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <p class="text-sm text-gray-600 mb-6">
            Pas de problème. Dites-nous simplement votre adresse email et nous vous enverrons un lien de réinitialisation de mot de passe qui vous permettra de choisir un nouveau.
        </p>

        <x-form-input
            name="email"
            type="email"
            label="Adresse Email"
            placeholder="vous@example.com"
            required
            autofocus
        />

        <x-button variant="primary" type="submit" class="w-full">
            Envoyer le lien de réinitialisation
        </x-button>
    </form>
</x-auth-card>
@endsection
    </div>
</div>
@endsection
