@extends('layouts.app')

@section('title', 'Modifier Groupe')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Modifier Groupe</h2>
        <a href="{{ route('admin.groupes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
            ← Retour
        </a>
    </div>

    <!-- Errors -->
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.groupes.update', $groupe) }}">
            @csrf
            @method('PATCH')

            <!-- Nom -->
            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom du Groupe</label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $groupe->nom) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="5" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $groupe->description ?? '') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded">
                    Enregistrer
                </button>
                <a href="{{ route('admin.groupes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <!-- Group Info -->
    <div class="mt-6 bg-white rounded-lg shadow p-6 max-w-2xl">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Informations du Groupe</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Admin du groupe</p>
                <p class="text-gray-900 font-medium">{{ $groupe->admin?->nom ?? 'Unknown' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nombre de membres</p>
                <p class="text-gray-900 font-medium">{{ $groupe->utilisateurs?->count() ?? 0 }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Date de création</p>
                <p class="text-gray-900 font-medium">{{ $groupe->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Dernière modification</p>
                <p class="text-gray-900 font-medium">{{ $groupe->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
