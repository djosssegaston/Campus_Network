<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Afficher les notifications de l'utilisateur
     */
    public function index(): View
    {
        $user = auth()->check() ? auth()->user() : null;
        
        $notifications = $user 
            ? Notification::where('utilisateur_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
            : collect();

        return view('notifications.index', [
            'notifications' => $notifications
        ]);
    }

    /**
     * Obtenir les notifications non lues en JSON
     */
    public function unread(): JsonResponse
    {
        $user = auth()->user();

        $notifications = Notification::where('utilisateur_id', $user->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function read(Notification $notification): RedirectResponse
    {
        if ($notification->utilisateur_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        $notification->marquerCommeLue();

        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function readAll(): RedirectResponse
    {
        Notification::where('utilisateur_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Toutes les notifications marquées comme lues');
    }

    /**
     * Supprimer une notification
     */
    public function destroy(Notification $notification): RedirectResponse
    {
        if ($notification->utilisateur_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification supprimée');
    }

    /**
     * Supprimer toutes les notifications lues
     */
    public function deleteAllRead(): RedirectResponse
    {
        Notification::where('utilisateur_id', auth()->id())
            ->whereNotNull('read_at')
            ->delete();

        return redirect()->back()->with('success', 'Notifications lues supprimées');    }
}