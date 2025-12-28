<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    use AuthenticatedUser;
    /**
     * Get user conversations.
     */
    public function conversations(): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        $conversations = $user->conversations()
            ->with(['utilisateurs', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest()
            ->get();

        return response()->json(['data' => $conversations]);
    }

    /**
     * Get conversation with messages.
     */
    public function show($id): JsonResponse
    {
        $conversation = Conversation::with(['utilisateurs', 'messages.expediteur'])
            ->findOrFail($id);

        // Check if user is part of conversation
        if (!$conversation->utilisateurs()->where('utilisateur_id', $this->userId())->exists()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json(['data' => $conversation]);
    }

    /**
     * Create a new conversation.
     */
    public function createConversation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'utilisateur_ids' => 'required|array',
            'utilisateur_ids.*' => 'exists:utilisateurs,id',
        ]);

        $conversation = Conversation::create([
            'titre' => $validated['titre'],
        ]);

        $conversation->utilisateurs()->attach($this->userId());
        $conversation->utilisateurs()->attach($validated['utilisateur_ids']);

        return response()->json([
            'message' => 'Conversation créée',
            'data' => $conversation,
        ], 201);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request, $conversationId): JsonResponse
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Check if user is part of conversation
        if (!$conversation->utilisateurs()->where('utilisateur_id', $this->userId())->exists()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'contenu' => 'required|string',
        ]);

        $message = $conversation->messages()->create([
            'expediteur_id' => $this->userId(),
            'contenu' => $validated['contenu'],
        ]);

        return response()->json([
            'message' => 'Message envoyé',
            'data' => $message->load('expediteur'),
        ], 201);
    }

    /**
     * Get a specific message.
     */
    public function getMessage($id): JsonResponse
    {
        $message = Message::with('expediteur')->findOrFail($id);

        return response()->json(['data' => $message]);
    }

    /**
     * Delete a message.
     */
    public function destroy($id): JsonResponse
    {
        $message = Message::findOrFail($id);

        // Check authorization - owner or admin
        $user = $this->user();
        
        if (!$user || ($message->expediteur_id !== $user->id && !$user->estAdmin())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $message->delete();

        return response()->json(['message' => 'Message supprimé']);
    }
}
