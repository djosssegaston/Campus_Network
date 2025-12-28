@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Paramètres Système</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PATCH')
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Nom de l'Application</label>
                <input type="text" name="app_name" required
                       class="w-full px-4 py-2 border rounded-lg"
                       value="{{ $settings['app_name'] ?? config('app.name') }}">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="app_description" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ $settings['app_description'] ?? '' }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Taille max upload (MB)</label>
                <input type="number" name="max_upload_size" min="1" max="1000"
                       class="w-full px-4 py-2 border rounded-lg"
                       value="{{ $settings['max_upload_size'] ?? '100' }}">
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="maintenance_mode" value="1" 
                           {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }} class="rounded">
                    <span class="ml-2">Mode maintenance</span>
                </label>
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="allow_user_registration" value="1" 
                           {{ ($settings['allow_user_registration'] ?? true) ? 'checked' : '' }} class="rounded">
                    <span class="ml-2">Autoriser l'enregistrement des utilisateurs</span>
                </label>
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="allow_group_creation" value="1" 
                           {{ ($settings['allow_group_creation'] ?? true) ? 'checked' : '' }} class="rounded">
                    <span class="ml-2">Autoriser la création de groupes</span>
                </label>
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="moderation_enabled" value="1" 
                           {{ ($settings['moderation_enabled'] ?? true) ? 'checked' : '' }} class="rounded">
                    <span class="ml-2">Modération activée</span>
                </label>
            </div>
            
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                Enregistrer
            </button>
        </form>
    </div>
    
    <div class="flex gap-4">
        <a href="{{ route('settings.logs') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
            Voir les Logs
        </a>
        <form method="POST" action="{{ route('settings.maintenance') }}" class="inline">
            @csrf
            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600"
                    onclick="return confirm('Êtes-vous sûr?')">
                Exécuter la Maintenance
            </button>
        </form>
    </div>
</div>
@endsection
