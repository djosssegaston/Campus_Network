<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Models\Publication;
use App\Models\Utilisateur;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    use AuthenticatedUser;

    /**
     * Recherche global à travers publications, utilisateurs, et groupes
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:255'],
            'type' => ['nullable', 'string', 'in:publication,utilisateur,groupe,all'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = $request->input('q');
        $type = $request->input('type', 'all');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $results = [
            'query' => $query,
            'type' => $type,
            'publications' => null,
            'utilisateurs' => null,
            'groupes' => null,
        ];

        // Recherche Publications
        if ($type === 'all' || $type === 'publication') {
            $publications = Publication::where('visibilite', '!=', 'prive')
                ->where(function ($q) use ($query) {
                    $q->where('contenu', 'like', "%{$query}%")
                      ->orWhereHas('utilisateur', function ($q) use ($query) {
                          $q->where('nom', 'like', "%{$query}%");
                      });
                })
                ->with(['utilisateur', 'groupe'])
                ->latest()
                ->paginate($perPage, ['*'], 'page', $page);

            $results['publications'] = [
                'data' => $publications->items(),
                'current_page' => $publications->currentPage(),
                'last_page' => $publications->lastPage(),
                'total' => $publications->total(),
            ];
        }

        // Recherche Utilisateurs
        if ($type === 'all' || $type === 'utilisateur') {
            $utilisateurs = Utilisateur::where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('filiere', 'like', "%{$query}%");
            })
            ->with('role')
            ->paginate($perPage, ['*'], 'page', $page);

            $results['utilisateurs'] = [
                'data' => $utilisateurs->items(),
                'current_page' => $utilisateurs->currentPage(),
                'last_page' => $utilisateurs->lastPage(),
                'total' => $utilisateurs->total(),
            ];
        }

        // Recherche Groupes
        if ($type === 'all' || $type === 'groupe') {
            $groupes = Groupe::where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->withCount('utilisateurs', 'publications')
            ->paginate($perPage, ['*'], 'page', $page);

            $results['groupes'] = [
                'data' => $groupes->items(),
                'current_page' => $groupes->currentPage(),
                'last_page' => $groupes->lastPage(),
                'total' => $groupes->total(),
            ];
        }

        return response()->json($results);
    }

    /**
     * Suggestions de recherche (autocomplete)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function suggestions(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $query = $request->input('q');
        $limit = 5;

        $suggestions = [
            'utilisateurs' => Utilisateur::where('nom', 'like', "%{$query}%")
                ->select('id', 'nom', 'avatar_url')
                ->limit($limit)
                ->get()
                ->toArray(),
            'groupes' => Groupe::where('nom', 'like', "%{$query}%")
                ->select('id', 'nom')
                ->limit($limit)
                ->get()
                ->toArray(),
            'publications' => Publication::where('visibilite', '!=', 'prive')
                ->where('contenu', 'like', "%{$query}%")
                ->select('id', 'contenu')
                ->limit($limit)
                ->get()
                ->toArray(),
        ];

        return response()->json($suggestions);
    }
}
