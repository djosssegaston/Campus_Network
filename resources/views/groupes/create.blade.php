@extends('layouts.app')

@section('title', 'Créer un Groupe')

@section('content')
<div class="p-8 max-w-2xl">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Créer un Groupe</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('groupes.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom du groupe</label>
                <input type="text" id="nom" name="nom" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror" value="{{ old('nom') }}" required>
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="visibilite" class="block text-sm font-medium text-gray-700 mb-2">Visibilité</label>
                <select id="visibilite" name="visibilite" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('visibilite') border-red-500 @enderror">
                    <option value="public" {{ old('visibilite') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="prive" {{ old('visibilite') === 'prive' ? 'selected' : '' }}>Privé</option>
                </select>
                @error('visibilite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Créer
                </button>
                <a href="{{ route('groupes.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
