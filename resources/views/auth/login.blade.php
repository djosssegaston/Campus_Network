@extends('layouts.auth')

@section('title', 'Connexion - Campus Network')

@section('content')
<x-auth-card 
    title="Connexion" 
    subtitle="Accédez à votre compte Campus Network"
    gradientFrom="from-blue-600"
    gradientTo="to-blue-700"
    :footer="true"
    footerText="Pas encore inscrit? "
    footerLink="{{ route('register') }}"
    footerLinkText="Créer un compte"
>
    @if ($errors->any())
        <x-alert type="error" title="Erreur de connexion">
            Vérifiez vos identifiants et réessayez.
        </x-alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <x-form-input
            name="email"
            type="email"
            label="Adresse Email"
            placeholder="votre@email.com"
            required
        />

        <x-form-input
            name="password"
            type="password"
            label="Mot de passe"
            placeholder="••••••••"
            required
        />

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-gray-700">Se souvenir de moi</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-700 font-medium transition">
                    Mot de passe oublié?
                </a>
            @endif
        </div>

        <x-button variant="primary" type="submit">
            Se connecter
        </x-button>
    </form>
</x-auth-card>
@endsection
