<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        $query = Utilisateur::with('role');

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('filiere', 'like', "%{$search}%");
        }

        // Filtre par rôle
        if ($request->has('role') && $request->role) {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('nom', $request->role);
            });
        }

        // Tri
        $sortBy = $request->get('sortBy', 'created_at');
        $sortOrder = $request->get('sortOrder', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15);
        $roles = Role::all();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'search' => $request->search ?? '',
                'role' => $request->role ?? '',
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
            ]
        ]);
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(Utilisateur $user)
    {
        $user->load('role', 'publications', 'groupes', 'commentaires');

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'stats' => [
                'publications' => $user->publications()->count(),
                'groupes' => $user->groupes()->count(),
                'commentaires' => $user->commentaires()->count(),
            ]
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $roles = Role::all();

        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles
        ]);
    }

    /**
     * Stocker un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:8|confirmed',
            'filiere' => 'required|string|max:255',
            'annee_etude' => 'required|integer|between:1,5',
            'role_id' => 'required|exists:roles,id',
        ], [
            'nom.required' => 'Le nom est requis',
            'email.required' => 'L\'email est requis',
            'email.unique' => 'Cet email est déjà utilisé',
            'mot_de_passe.required' => 'Le mot de passe est requis',
            'mot_de_passe.confirmed' => 'Les mots de passe ne correspondent pas',
        ]);

        $user = Utilisateur::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'mot_de_passe' => Hash::make($validated['mot_de_passe']),
            'filiere' => $validated['filiere'],
            'annee_etude' => $validated['annee_etude'],
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('users.index')
                       ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Utilisateur $user)
    {
        $roles = Role::all();

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, Utilisateur $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $user->id,
            'filiere' => 'required|string|max:255',
            'annee_etude' => 'required|integer|between:1,5',
            'role_id' => 'required|exists:roles,id',
            'mot_de_passe' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'filiere' => $validated['filiere'],
            'annee_etude' => $validated['annee_etude'],
            'role_id' => $validated['role_id'],
        ]);

        // Mettre à jour le mot de passe si fourni
        if (!empty($validated['mot_de_passe'])) {
            $user->update(['mot_de_passe' => Hash::make($validated['mot_de_passe'])]);
        }

        return redirect()->route('users.index')
                       ->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(Utilisateur $user)
    {
        // Empêcher la suppression de soi-même
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                           ->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $user->delete();

        return redirect()->route('users.index')
                       ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Restaurer un utilisateur supprimé
     */
    public function restore($userId)
    {
        $user = Utilisateur::withTrashed()->find($userId);

        if (!$user) {
            return redirect()->route('users.index')
                           ->with('error', 'Utilisateur non trouvé');
        }

        $user->restore();

        return redirect()->route('users.index')
                       ->with('success', 'Utilisateur restauré avec succès');
    }

    /**
     * Changer le rôle d'un utilisateur
     */
    public function changeRole(Request $request, Utilisateur $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update(['role_id' => $validated['role_id']]);

        return redirect()->route('users.index')
                       ->with('success', 'Rôle mis à jour avec succès');
    }
}
