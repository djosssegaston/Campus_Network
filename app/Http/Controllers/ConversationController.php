<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Utilisateur;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des conversations
     */
    public function index()
    {
        $conversations = auth()->user()->conversations()
            ->with(['utilisateurs', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->paginate(15);

        return Inertia::render('Conversations/Index', [
            'conversations' => $conversations
        ]);
    }

    /**
     * Afficher une conversation spécifique
     */
    public function show(Conversation $conversation)
    {
        // Vérifier que l'utilisateur est participant
        if (!$conversation->utilisateurs->contains(auth()->id())) {
            abort(403, 'Vous n\'avez pas accès à cette conversation');
        }

        $conversation->load(['utilisateurs', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        return Inertia::render('Conversations/Show', [
            'conversation' => $conversation,
            'messages' => $conversation->messages,
        ]);
    }

    /**
     * Créer une nouvelle conversation
     */
    public function create()
    {
        $users = Utilisateur::where('id', '!=', auth()->id())
            ->orderBy('nom')
            ->get();

        return Inertia::render('Conversations/Create', [
            'users' => $users
        ]);
    }

    /**
     * Stocker une nouvelle conversation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => 'required|exists:utilisateurs,id|different:auth_id',
            'message' => 'required|string|min:1|max:1000',
        ], [
            'participant_id.required' => 'Veuillez sélectionner un participant',
            'participant_id.exists' => 'Le participant n\'existe pas',
            'message.required' => 'Le message est requis',
            'message.min' => 'Le message ne peut pas être vide',
        ]);

        $participant = Utilisateur::findOrFail($validated['participant_id']);

        // Vérifier s'il existe déjà une conversation entre ces deux utilisateurs
        $existingConversation = Conversation::whereHas('utilisateurs', function ($query) {
            $query->where('utilisateur_id', auth()->id());
        })->whereHas('utilisateurs', function ($query) use ($participant) {
            $query->where('utilisateur_id', $participant->id);
        })->first();

        if ($existingConversation) {
            return redirect()->route('conversations.show', $existingConversation->id)
                           ->with('info', 'La conversation existe déjà');
        }

        // Créer la conversation
        $conversation = Conversation::create([
            'titre' => 'Discussion avec ' . $participant->nom,
        ]);

        // Ajouter les participants
        $conversation->utilisateurs()->attach([
            auth()->id(),
            $participant->id
        ]);

        // Ajouter le premier message
        Message::create([
            'conversation_id' => $conversation->id,
            'utilisateur_id' => auth()->id(),
            'contenu' => $validated['message'],
        ]);

        return redirect()->route('conversations.show', $conversation->id)
                       ->with('success', 'Conversation démarrée avec succès');
    }

    /**
     * Ajouter un message à une conversation
     */
    public function addMessage(Request $request, Conversation $conversation)
    {
        // Vérifier que l'utilisateur est participant
        if (!$conversation->utilisateurs->contains(auth()->id())) {
            abort(403, 'Vous n\'avez pas accès à cette conversation');
        }

        $validated = $request->validate([
            'contenu' => 'required|string|min:1|max:1000',
        ], [
            'contenu.required' => 'Le message est requis',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'utilisateur_id' => auth()->id(),
            'contenu' => $validated['contenu'],
        ]);

        $conversation->update(['updated_at' => now()]);

        return redirect()->route('conversations.show', $conversation->id)
                       ->with('success', 'Message envoyé');
    }

    /**
     * Supprimer une conversation
     */
    public function destroy(Conversation $conversation)
    {
        // Vérifier que l'utilisateur est participant
        if (!$conversation->utilisateurs->contains(auth()->id())) {
            abort(403, 'Vous n\'avez pas accès à cette conversation');
        }

        // Détacher l'utilisateur de la conversation
        $conversation->utilisateurs()->detach(auth()->id());

        // Si plus aucun utilisateur, supprimer la conversation
        if ($conversation->utilisateurs()->count() === 0) {
            $conversation->delete();
        }

        return redirect()->route('conversations.index')
                       ->with('success', 'Conversation supprimée');
    }
}