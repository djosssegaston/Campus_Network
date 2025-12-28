@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Signalements</h1>
    
    <div class="mb-6">
        <form method="GET" class="flex gap-2">
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvés</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetés</option>
            </select>
            
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                Filtrer
            </button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Utilisateur</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Raison</th>
                        <th class="px-4 py-2 text-left">Statut</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($signalements as $signalement)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $signalement->utilisateur->nom }}</td>
                            <td class="px-4 py-2">{{ ucfirst($signalement->type) }}</td>
                            <td class="px-4 py-2">{{ Str::limit($signalement->raison, 50) }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm {{ 
                                    $signalement->status == 'pending' ? 'bg-orange-100 text-orange-800' :
                                    ($signalement->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')
                                }}">
                                    {{ ucfirst($signalement->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('moderation.report-show', $signalement) }}" class="text-blue-500 hover:text-blue-700">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center">Aucun signalement</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $signalements->links() }}
        </div>
    </div>
</div>
@endsection
