@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Rôles</h1>
    
    <div class="mb-6">
        <a href="{{ route('roles.create') }}" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
            + Nouveau Rôle
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Nom</th>
                        <th class="px-4 py-2 text-left">Slug</th>
                        <th class="px-4 py-2 text-left">Permissions</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $role->nom }}</td>
                            <td class="px-4 py-2">{{ $role->slug }}</td>
                            <td class="px-4 py-2">
                                <span class="bg-gray-100 px-2 py-1 rounded text-sm">
                                    {{ $role->permissions->count() }} permissions
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('roles.edit', $role) }}" class="text-blue-500 hover:text-blue-700 mr-2">Éditer</a>
                                <form method="POST" action="{{ route('roles.destroy', $role) }}" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center">Aucun rôle trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection
