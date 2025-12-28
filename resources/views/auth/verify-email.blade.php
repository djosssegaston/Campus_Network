@extends('layouts.auth')

@section('title', 'Vérifier votre Email - Campus Network')

@section('content')
<x-auth-card 
    title="Vérifier votre Email" 
    subtitle="Confirmez votre adresse email pour accéder à votre compte"
    gradientFrom="from-blue-600"
    gradientTo="to-blue-700"
>
    @if (session('status') == 'verification-link-sent')
        <x-alert type="success" title="Email envoyé">
            Un lien de vérification a été envoyé à votre adresse email!
        </x-alert>
    @endif

    <p class="text-sm text-gray-600 mb-6">
        Avant de continuer, veuillez vérifier votre adresse email en cliquant sur le lien d'activation que nous avons envoyé.
    </p>

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-button variant="primary" type="submit" class="w-full">
                Renvoyer l'email de vérification
            </x-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button variant="secondary" type="submit" class="w-full">
                Se déconnecter
            </x-button>
        </form>
    </div>
</x-auth-card>
@endsection
