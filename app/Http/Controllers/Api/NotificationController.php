<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    use AuthenticatedUser;

    /**
     * Get user notifications
     */
    public function index(): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        $notifications = Notification::where('utilisateur_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json(['data' => $notifications]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id): JsonResponse
    {
        $user = $this->user();
        
        $notification = Notification::where('utilisateur_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->marquerCommeLue();

        return response()->json([
            'message' => 'Notification marquée comme lue',
            'data' => $notification
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = $this->user();

        Notification::where('utilisateur_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Toutes les notifications marquées comme lues']);
    }
}
