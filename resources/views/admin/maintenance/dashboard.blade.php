@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tableau de Bord Maintenance</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">État du Système</h3>
            <div class="space-y-2">
                @foreach($health as $component => $status)
                    <div class="flex justify-between items-center">
                        <span class="capitalize">{{ $component }}:</span>
                        <span class="px-2 py-1 rounded text-sm {{ $status['status'] == 'OK' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $status['status'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Informations Système</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>PHP:</span>
                    <span class="font-mono">{{ $systemInfo['php_version'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Laravel:</span>
                    <span class="font-mono">{{ $systemInfo['laravel_version'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>BD:</span>
                    <span class="font-mono">{{ $systemInfo['database_size'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Stockage:</span>
                    <span class="font-mono">{{ $systemInfo['storage_usage'] }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Outils de Maintenance</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST" action="{{ route('maintenance.cache') }}" class="inline">
                @csrf
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600">
                    🔄 Optimiser le Cache
                </button>
            </form>
            
            <form method="POST" action="{{ route('maintenance.migrate') }}" class="inline">
                @csrf
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600">
                    🔧 Exécuter les Migrations
                </button>
            </form>
            
            <form method="POST" action="{{ route('maintenance.optimize-db') }}" class="inline">
                @csrf
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600"
                        onclick="return confirm('Êtes-vous sûr?')">
                    ⚡ Optimiser la BD
                </button>
            </form>
            
            <form method="POST" action="{{ route('maintenance.cleanup-inactive') }}" class="inline">
                @csrf
                <button type="submit" class="w-full bg-orange-500 text-white px-4 py-3 rounded-lg hover:bg-orange-600"
                        onclick="return confirm('Êtes-vous sûr?')">
                    🗑️ Nettoyer Comptes Inactifs
                </button>
            </form>
            
            <form method="POST" action="{{ route('maintenance.cleanup-files') }}" class="inline">
                @csrf
                <button type="submit" class="w-full bg-orange-500 text-white px-4 py-3 rounded-lg hover:bg-orange-600"
                        onclick="return confirm('Êtes-vous sûr?')">
                    📁 Nettoyer Fichiers
                </button>
            </form>
            
            <a href="{{ route('maintenance.report') }}" class="block bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 text-center">
                📊 Générer un Rapport
            </a>
        </div>
    </div>
</div>
@endsection
