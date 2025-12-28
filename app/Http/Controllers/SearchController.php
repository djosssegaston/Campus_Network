<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Utilisateur;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Affiche la page de recherche
     */
    public function index(Request $request): View
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');
        $page = $request->input('page', 1);
        $perPage = 10;

        $results = [
            'publications' => null,
            'utilisateurs' => null,
            'groupes' => null,
        ];

        if (!empty($query)) {
            // Recherche Publications
            if ($type === 'all' || $type === 'publication') {
                $results['publications'] = Publication::where('visibilite', '!=', 'prive')
                    ->where(function ($q) use ($query) {
                        $q->where('contenu', 'like', "%{$query}%")
                          ->orWhereHas('utilisateur', function ($q) use ($query) {
                              $q->where('nom', 'like', "%{$query}%");
                          });
                    })
                    ->with(['utilisateur', 'groupe'])
                    ->latest()
                    ->paginate($perPage);
            }

            // Recherche Utilisateurs
            if ($type === 'all' || $type === 'utilisateur') {
                $results['utilisateurs'] = Utilisateur::where(function ($q) use ($query) {
                    $q->where('nom', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('filiere', 'like', "%{$query}%");
                })
                ->with('role')
                ->paginate($perPage);
            }

            // Recherche Groupes
            if ($type === 'all' || $type === 'groupe') {
                $results['groupes'] = Groupe::where(function ($q) use ($query) {
                    $q->where('nom', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->withCount('utilisateurs', 'publications')
                ->paginate($perPage);
            }
        }

        return view('search.index', [
            'query' => $query,
            'type' => $type,
            'results' => $results,
        ]);
    }
}
