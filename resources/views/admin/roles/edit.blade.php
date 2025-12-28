@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Éditer le Rôle: {{ $role->nom }}</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PATCH')
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Nom</label>
                <input type="text" name="nom" required
                       class="w-full px-4 py-2 border rounded-lg @error('nom') border-red-500 @enderror"
                       value="{{ $role->nom }}">
                @error('nom')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Slug</label>
                <input type="text" name="slug" required
                       class="w-full px-4 py-2 border rounded-lg @error('slug') border-red-500 @enderror"
                       value="{{ $role->slug }}">
                @error('slug')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2 border rounded-lg">{{ $role->description }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Permissions</label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($permissions as $permission)
                        <label class="flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                   class="rounded">
                            <span class="ml-2">{{ $permission->nom }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Enregistrer
                </button>
                <a href="{{ route('roles.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
