<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Utilisateur;
use App\Models\Groupe;
use App\Models\Message;
use App\Models\GroupeMessage;
use Illuminate\View\View;

class AdminViewController extends Controller
{
    /**
     * Display the admin dashboard with statistics
     */
    public function dashboard(): View
    {
        $totalUsers = Utilisateur::count();
        $totalPublications = Publication::count();
        $totalGroupes = Groupe::count();
        $totalMessages = Message::count() + GroupeMessage::count();
        $totalComments = \App\Models\Commentaire::count();
        $totalShares = \App\Models\Partage::count();
        
        // Recent activities
        $recentUsers = Utilisateur::latest()->take(5)->get();
        $recentPublications = Publication::latest()->take(5)->get();
        $recentGroupes = Groupe::latest()->take(5)->get();
        
        // Statistics
        $usersThisMonth = Utilisateur::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        
        $publicationsThisMonth = Publication::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalPublications' => $totalPublications,
            'totalGroupes' => $totalGroupes,
            'totalMessages' => $totalMessages,
            'totalComments' => $totalComments,
            'totalShares' => $totalShares,
            'usersThisMonth' => $usersThisMonth,
            'publicationsThisMonth' => $publicationsThisMonth,
            'recentUsers' => $recentUsers,
            'recentPublications' => $recentPublications,
            'recentGroupes' => $recentGroupes
        ]);
    }

    /**
     * Display users management page
     */
    public function users(?\Illuminate\Http\Request $request): View
    {
        $query = Utilisateur::query();
        
        if ($request?->filled('search')) {
            $search = $request->get('search');
            $query->where('nom', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }
        
        $users = $query->paginate(20);
        
        return view('admin.users.index', [
            'users' => $users,
            'search' => $request?->get('search', '')
        ]);
    }

    /**
     * Display publications management page
     */
    public function publications(?\Illuminate\Http\Request $request): View
    {
        $query = Publication::query()->with('utilisateur');
        
        if ($request?->filled('search')) {
            $search = $request->get('search');
            $query->where('contenu', 'like', "%$search%");
        }
        
        $publications = $query->latest()->paginate(20);
        
        return view('admin.publications.index', [
            'publications' => $publications,
            'search' => $request?->get('search', '')
        ]);
    }

    /**
     * Display groupes management page
     */
    public function groupes(?\Illuminate\Http\Request $request): View
    {
        $query = Groupe::query()->with('admin', 'utilisateurs');
        
        if ($request?->filled('search')) {
            $search = $request->get('search');
            $query->where('nom', 'like', "%$search%");
        }
        
        $groupes = $query->latest()->paginate(20);
        
        return view('admin.groupes.index', [
            'groupes' => $groupes,
            'search' => $request?->get('search', '')
        ]);
    }

    /**
     * Display messages management page
     */
    public function messages(?\Illuminate\Http\Request $request): View
    {
        $query = Message::query()->with('utilisateur');
        
        if ($request?->filled('search')) {
            $search = $request->get('search');
            $query->where('contenu', 'like', "%$search%");
        }
        
        $messages = $query->latest()->paginate(20);
        
        return view('admin.messages.index', [
            'messages' => $messages,
            'search' => $request?->get('search', '')
        ]);
    }

    /**
     * Delete a user
     */
    public function deleteUser(\App\Models\Utilisateur $user)
    {
        $user->delete();
        return redirect()->back()->with('success', "Utilisateur {$user->nom} supprimé");
    }

    /**
     * Show user edit form
     */
    public function editUser(\App\Models\Utilisateur $user)
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update a user
     */
    public function updateUser(\Illuminate\Http\Request $request, \App\Models\Utilisateur $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $user->id,
            'telephone' => 'nullable|string|max:20'
        ]);

        $user->update($validated);
        return redirect()->route('users.index')->with('success', "Utilisateur {$user->nom} modifié");
    }

    /**
     * Delete a publication
     */
    public function deletePublication(\App\Models\Publication $publication)
    {
        $publication->delete();
        return redirect()->back()->with('success', 'Publication supprimée');
    }

    /**
     * Show publication edit form
     */
    public function editPublication(\App\Models\Publication $publication)
    {
        return view('admin.publications.edit', ['publication' => $publication]);
    }

    /**
     * Update a publication
     */
    public function updatePublication(\Illuminate\Http\Request $request, \App\Models\Publication $publication)
    {
        $validated = $request->validate([
            'contenu' => 'required|string'
        ]);

        $publication->update($validated);
        return redirect()->route('publications.index')->with('success', 'Publication modifiée');
    }

    /**
     * Delete a groupe
     */
    public function deleteGroupe(\App\Models\Groupe $groupe)
    {
        $groupe->delete();
        return redirect()->back()->with('success', "Groupe {$groupe->nom} supprimé");
    }

    /**
     * Show groupe edit form
     */
    public function editGroupe(\App\Models\Groupe $groupe)
    {
        return view('admin.groupes.edit', ['groupe' => $groupe]);
    }

    /**
     * Update a groupe
     */
    public function updateGroupe(\Illuminate\Http\Request $request, \App\Models\Groupe $groupe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $groupe->update($validated);
        return redirect()->route('groupes.index')->with('success', "Groupe {$groupe->nom} modifié");
    }

    /**
     * Delete a message
     */
    public function deleteMessage(\App\Models\Message $message)
    {
        $message->delete();
        return redirect()->back()->with('success', 'Message supprimé');
    }
}
