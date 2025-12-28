<?php

namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Models\Publication;
use App\Models\Utilisateur;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ModerationController extends Controller
{
    /**
     * Afficher le tableau de bord de modération
     */
    public function dashboard(): View
    {
        $this->authorize('viewModeration');
        
        $pendingReports = Signalement::where('status', 'pending')->count();
        $totalReports = Signalement::count();
        $flaggedContent = Publication::where('is_flagged', true)->count();
        $bannedUsers = Utilisateur::where('is_banned', true)->count();
        
        return view('admin.moderation.dashboard', compact(
            'pendingReports',
            'totalReports',
            'flaggedContent',
            'bannedUsers'
        ));
    }

    /**
     * Afficher les signalements
     */
    public function reports(Request $request): View
    {
        $this->authorize('viewModeration');
        
        $query = Signalement::with(['utilisateur', 'publication']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $signalements = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.moderation.reports', compact('signalements'));
    }

    /**
     * Afficher les détails d'un signalement
     */
    public function showReport(Signalement $signalement): View
    {
        $this->authorize('viewModeration');
        
        return view('admin.moderation.report-detail', compact('signalement'));
    }

    /**
     * Approuver un signalement (action)
     */
    public function approveReport(Request $request, Signalement $signalement): RedirectResponse
    {
        $this->authorize('viewModeration');

        $validated = $request->validate([
            'action' => 'required|in:delete,hide,warn,ban',
            'reason' => 'required|string|max:1000',
        ]);

        $signalement->update([
            'status' => 'approved',
            'action_taken' => $validated['action'],
            'moderated_at' => now(),
            'moderated_by' => auth()->id(),
        ]);

        // Exécuter l'action
        $this->executeModeration($signalement, $validated['action'], $validated['reason']);

        return redirect()->route('moderation.reports')->with('success', 'Signalement traité avec succès');
    }

    /**
     * Rejeter un signalement
     */
    public function rejectReport(Request $request, Signalement $signalement): RedirectResponse
    {
        $this->authorize('viewModeration');

        $signalement->update([
            'status' => 'rejected',
            'moderated_at' => now(),
            'moderated_by' => auth()->id(),
        ]);

        return redirect()->route('moderation.reports')->with('success', 'Signalement rejeté');
    }

    /**
     * Exécuter l'action de modération
     */
    private function executeModeration(Signalement $signalement, string $action, string $reason): void
    {
        if ($signalement->publication_id) {
            $publication = Publication::find($signalement->publication_id);
            
            match ($action) {
                'delete' => $publication?->delete(),
                'hide' => $publication?->update(['is_hidden' => true]),
                'warn' => $this->warnUser($signalement->utilisateur_id, $reason),
                'ban' => $this->banUser($signalement->utilisateur_id, $reason),
                default => null,
            };
        }
    }

    /**
     * Avertir un utilisateur
     */
    private function warnUser(int $userId, string $reason): void
    {
        $user = Utilisateur::find($userId);
        if ($user) {
            $user->increment('warning_count');
            
            if ($user->warning_count >= 3) {
                $this->banUser($userId, 'Trois avertissements atteints');
            }
        }
    }

    /**
     * Bannir un utilisateur
     */
    public function banUser(int $userId, string $reason = 'Violation des conditions'): void
    {
        Utilisateur::where('id', $userId)->update([
            'is_banned' => true,
            'ban_reason' => $reason,
            'banned_at' => now(),
        ]);
    }

    /**
     * Débannir un utilisateur
     */
    public function unbanUser(Utilisateur $utilisateur): RedirectResponse
    {
        $this->authorize('viewModeration');
        
        $utilisateur->update([
            'is_banned' => false,
            'ban_reason' => null,
            'banned_at' => null,
        ]);

        return back()->with('success', 'Utilisateur débanni');
    }

    /**
     * Afficher les contenus signalés
     */
    public function flaggedContent(Request $request): View
    {
        $this->authorize('viewModeration');
        
        $publications = Publication::where('is_flagged', true)
            ->with('utilisateur')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.moderation.flagged-content', compact('publications'));
    }

    /**
     * Approuver un contenu signalé
     */
    public function approveFlaggedContent(Publication $publication): RedirectResponse
    {
        $this->authorize('viewModeration');
        
        $publication->update(['is_flagged' => false]);

        return back()->with('success', 'Contenu approuvé');
    }

    /**
     * Supprimer un contenu signalé
     */
    public function deleteFlaggedContent(Publication $publication): RedirectResponse
    {
        $this->authorize('viewModeration');
        
        $publication->delete();

        return back()->with('success', 'Contenu supprimé');
    }

    /**
     * Afficher les utilisateurs bannîs
     */
    public function bannedUsers(): View
    {
        $this->authorize('viewModeration');
        
        $users = Utilisateur::where('is_banned', true)
            ->with('role')
            ->paginate(20);
        
        return view('admin.moderation.banned-users', compact('users'));
    }
}
