<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Publication;
use App\Models\Groupe;
use App\Models\Message;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Afficher le tableau de bord d'analytics
     */
    public function dashboard(Request $request): View
    {
        $this->authorize('viewAnalytics');
        
        $period = $request->get('period', '30');
        $since = now()->subDays((int)$period);
        
        // Statistiques globales
        $stats = [
            'total_users' => Utilisateur::count(),
            'total_publications' => Publication::count(),
            'total_groups' => Groupe::count(),
            'total_messages' => Message::count(),
        ];
        
        // Croissance
        $stats['new_users'] = Utilisateur::where('created_at', '>=', $since)->count();
        $stats['new_publications'] = Publication::where('created_at', '>=', $since)->count();
        $stats['new_groups'] = Groupe::where('created_at', '>=', $since)->count();
        
        // Activité
        $stats['active_users'] = Utilisateur::where('last_seen', '>=', now()->subDays(7))->count();
        $stats['total_reactions'] = Reaction::count();
        
        return view('admin.analytics.dashboard', compact('stats', 'period'));
    }

    /**
     * Afficher les analytics détaillées des utilisateurs
     */
    public function users(Request $request): View
    {
        $this->authorize('viewAnalytics');
        
        $period = $request->get('period', '30');
        $since = now()->subDays((int)$period);
        
        // Top utilisateurs par publications
        $topPublishers = Utilisateur::withCount(['publications' => function ($q) use ($since) {
            $q->where('created_at', '>=', $since);
        }])
        ->orderBy('publications_count', 'desc')
        ->limit(10)
        ->get();
        
        // Top utilisateurs par messages
        $topMessagers = Utilisateur::withCount(['messages' => function ($q) use ($since) {
            $q->where('created_at', '>=', $since);
        }])
        ->orderBy('messages_count', 'desc')
        ->limit(10)
        ->get();
        
        // Croissance des utilisateurs par jour
        $userGrowth = Utilisateur::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.analytics.users', compact('topPublishers', 'topMessagers', 'userGrowth', 'period'));
    }

    /**
     * Afficher les analytics des publications
     */
    public function publications(Request $request): View
    {
        $this->authorize('viewAnalytics');
        
        $period = $request->get('period', '30');
        $since = now()->subDays((int)$period);
        
        // Top publications
        $topPublications = Publication::withCount(['reactions', 'commentaires'])
            ->where('created_at', '>=', $since)
            ->orderBy('reactions_count', 'desc')
            ->limit(10)
            ->get();
        
        // Publications par jour
        $publicationGrowth = Publication::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Types de publication les plus courants
        $publicationTypes = Publication::selectRaw('type, COUNT(*) as count')
            ->where('created_at', '>=', $since)
            ->groupBy('type')
            ->get();
        
        return view('admin.analytics.publications', compact('topPublications', 'publicationGrowth', 'publicationTypes', 'period'));
    }

    /**
     * Afficher les analytics des groupes
     */
    public function groups(Request $request): View
    {
        $this->authorize('viewAnalytics');
        
        $period = $request->get('period', '30');
        $since = now()->subDays((int)$period);
        
        // Top groupes
        $topGroups = Groupe::withCount(['utilisateurs', 'publications' => function ($q) use ($since) {
            $q->where('created_at', '>=', $since);
        }])
        ->orderBy('utilisateurs_count', 'desc')
        ->limit(10)
        ->get();
        
        // Croissance des groupes
        $groupGrowth = Groupe::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.analytics.groups', compact('topGroups', 'groupGrowth', 'period'));
    }

    /**
     * Afficher les analytics d'engagement
     */
    public function engagement(Request $request): View
    {
        $this->authorize('viewAnalytics');
        
        $period = $request->get('period', '30');
        $since = now()->subDays((int)$period);
        
        // Engagement par type
        $engagementByType = Reaction::selectRaw('type, COUNT(*) as count')
            ->where('created_at', '>=', $since)
            ->groupBy('type')
            ->get();
        
        // Utilisateurs les plus engagés
        $topEngagers = Utilisateur::selectRaw('utilisateurs.*, COUNT(reactions.id) as reaction_count')
            ->leftJoin('reactions', 'utilisateurs.id', '=', 'reactions.utilisateur_id')
            ->where('reactions.created_at', '>=', $since)
            ->groupBy('utilisateurs.id')
            ->orderBy('reaction_count', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.analytics.engagement', compact('engagementByType', 'topEngagers', 'period'));
    }

    /**
     * Exporter les analytics
     */
    public function export(Request $request)
    {
        $this->authorize('viewAnalytics');
        
        $period = $request->get('period', '30');
        $since = now()->subDays((int)$period);
        
        $data = [
            'date_export' => now()->format('Y-m-d H:i:s'),
            'period_days' => $period,
            'total_users' => Utilisateur::count(),
            'new_users' => Utilisateur::where('created_at', '>=', $since)->count(),
            'total_publications' => Publication::count(),
            'new_publications' => Publication::where('created_at', '>=', $since)->count(),
            'total_groups' => Groupe::count(),
            'total_messages' => Message::count(),
            'total_reactions' => Reaction::count(),
        ];
        
        return response()->json($data);
    }
}
