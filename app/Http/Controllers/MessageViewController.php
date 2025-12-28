<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Utilisateur;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageViewController extends Controller
{
    /**
     * Display user conversations.
     */
    public function index(): View
    {
        $userId = auth()->user()?->id;
        
        if (!$userId) {
            return view('messages.index', ['conversations' => collect()]);
        }

        $conversations = Conversation::whereHas('utilisateurs', function ($query) use ($userId) {
            $query->where('utilisateur_id', $userId);
        })
            ->with('utilisateurs', 'messages.expediteur')
            ->latest('updated_at')
            ->get();

        // Get first conversation if exists to show in preview
        $conversation = $conversations->first();

        return view('messages.index', [
            'conversations' => $conversations,
            'conversation' => $conversation
        ]);
    }

    /**
     * Show specific conversation.
     */
    public function show(Conversation $conversation): View
    {
        // Charger les relations nécessaires
        $conversation->load('utilisateurs', 'messages.expediteur');

        // Check authorization
        if (!$conversation->utilisateurs->contains(auth()->user())) {
            abort(403, 'Non autorisé');
        }

        $userId = auth()->user()?->id;
        $conversations = Conversation::whereHas('utilisateurs', function ($query) use ($userId) {
            $query->where('utilisateur_id', $userId);
        })
            ->with('utilisateurs', 'messages.expediteur')
            ->latest('updated_at')
            ->get();

        // Mark messages as read
        $conversation->messages()
            ->where('expediteur_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', [
            'conversation' => $conversation,
            'conversations' => $conversations
        ]);
    }

    /**
     * Show users to start conversation.
     */
    public function create(): View
    {
        $userId = auth()->id();
        
        // Get paginated users (excluding self)
        $utilisateurs = Utilisateur::where('id', '!=', $userId)
            ->orderBy('nom')
            ->paginate(12);

        // Optimize: Load all user conversations in ONE query
        $userConversations = auth()->user()
            ->conversations()
            ->with('utilisateurs')
            ->get();

        // Build a map of user IDs that already have conversations with current user
        // This prevents N+1 queries in the view
        $conversationMap = [];
        foreach ($userConversations as $conversation) {
            // Get the other user's ID from this conversation
            foreach ($conversation->utilisateurs as $user) {
                if ($user->id !== $userId) {
                    $conversationMap[$user->id] = $conversation->id;
                }
            }
        }

        return view('messages.create', [
            'utilisateurs' => $utilisateurs,
            'conversationMap' => $conversationMap
        ]);
    }

    /**
     * Start new conversation with user.
     */
    public function store(Utilisateur $user): RedirectResponse
    {
        // Validation: Cannot start conversation with self
        if ($user->id === auth()->id()) {
            Log::warning('Tentative de démarrer conversation avec soi-même', [
                'user_id' => auth()->id()
            ]);
            return redirect()->back()->with('error', 'Vous ne pouvez pas démarrer une conversation avec vous-même');
        }

        try {
            // Check if conversation already exists between these two users
            $existing = Conversation::whereHas('utilisateurs', function ($query) use ($user) {
                $query->where('utilisateur_id', $user->id);
            })
                ->whereHas('utilisateurs', function ($query) {
                    $query->where('utilisateur_id', auth()->id());
                })
                ->first();

            if ($existing) {
                Log::info('Conversation existante trouvée', [
                    'conversation_id' => $existing->id,
                    'initiator' => auth()->id(),
                    'recipient' => $user->id
                ]);
                return redirect()->route('messages.show', $existing)
                    ->with('info', 'Conversation existante ouverte');
            }

            // Create new conversation with transaction for data integrity
            $conversation = DB::transaction(function () use ($user) {
                // Create conversation
                $conv = Conversation::create([
                    'titre' => null // Private conversation between 2 users
                ]);

                // Attach both users to the conversation
                $attachResult = $conv->utilisateurs()->attach([
                    auth()->id(),
                    $user->id
                ]);

                Log::debug('Utilisateurs attachés à la conversation', [
                    'conversation_id' => $conv->id,
                    'users' => [auth()->id(), $user->id],
                    'attach_result' => $attachResult
                ]);

                return $conv;
            });

            // Verify attachment was successful - critical check
            $attachedCount = $conversation->utilisateurs()->count();
            if ($attachedCount !== 2) {
                Log::error('Attachement incomplet - suppression de la conversation', [
                    'conversation_id' => $conversation->id,
                    'expected' => 2,
                    'actual' => $attachedCount
                ]);
                $conversation->delete();
                return redirect()->back()->with(
                    'error',
                    'Erreur lors de la création de la conversation. Veuillez réessayer.'
                );
            }

            Log::info('Nouvelle conversation créée avec succès', [
                'conversation_id' => $conversation->id,
                'initiator' => auth()->id(),
                'recipient' => $user->id,
                'users_attached' => $attachedCount
            ]);

            return redirect()->route('messages.show', $conversation)
                ->with('success', 'Conversation démarrée avec ' . $user->nom . ' ✨');

        } catch (\Exception $e) {
            Log::error('Erreur critique lors de la création de conversation', [
                'user_id' => auth()->id(),
                'recipient_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with(
                'error',
                'Une erreur est survenue lors de la création de la conversation. Veuillez réessayer.'
            );
        }
    }
}
