<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\Utilisateur;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Store a new message.
     */
    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $recipientId = $validated['recipient_id'];

        // Prevent self-messaging
        if ($recipientId === auth()->id()) {
            Log::warning('Auto-message attempt', [
                'user_id' => auth()->id()
            ]);
            return redirect()->back()->with('error', 'Vous ne pouvez pas envoyer un message à vous-même');
        }

        try {
            // Use transaction to ensure data consistency
            $result = DB::transaction(function () use ($recipientId, $validated) {
                // Get or create conversation between two users
                $conversation = Conversation::whereHas('utilisateurs', function ($q) use ($recipientId) {
                    $q->where('utilisateur_id', $recipientId);
                })
                    ->whereHas('utilisateurs', function ($q) {
                        $q->where('utilisateur_id', auth()->id());
                    })
                    ->first();

                if (!$conversation) {
                    // Create new conversation if doesn't exist
                    $conversation = Conversation::create();
                    $conversation->utilisateurs()->attach([auth()->id(), $recipientId]);
                    
                    // Verify attachment
                    if ($conversation->utilisateurs()->count() !== 2) {
                        throw new \Exception('Erreur lors de l\'attachement des utilisateurs');
                    }
                    
                    Log::info('Nouvelle conversation créée automatiquement', [
                        'conversation_id' => $conversation->id,
                        'initiator' => auth()->id(),
                        'recipient' => $recipientId
                    ]);
                }

                // Create message
                $message = $conversation->messages()->create([
                    'expediteur_id' => auth()->id(),
                    'contenu' => $validated['contenu'],
                ]);

                if (!$message) {
                    throw new \Exception('Erreur lors de la création du message');
                }

                Log::debug('Message créé', [
                    'message_id' => $message->id,
                    'conversation_id' => $conversation->id
                ]);

                return $conversation;
            });

            return redirect()->route('messages.show', $result)
                ->with('success', 'Message envoyé avec succès! 💬');
        } catch (\Exception $e) {
            Log::error('Erreur lors du stockage du message', [
                'user_id' => auth()->id(),
                'recipient_id' => $recipientId,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'envoi du message');
        }
    }

    /**
     * Delete a message.
     */
    public function destroy(Message $message): RedirectResponse
    {
        // Authorization check
        if ($message->expediteur_id !== auth()->id() && !auth()->user()->estAdmin()) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        $conversationId = $message->conversation_id;
        $message->delete();

        return redirect()->route('messages.show', $conversationId)->with('success', 'Message supprimé!');
    }
}
