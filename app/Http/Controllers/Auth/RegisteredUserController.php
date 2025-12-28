<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:utilisateurs',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => 'required|accepted',
        ]);

        // Récupérer le rôle étudiant par défaut
        $roleEtudiant = Role::where('slug', 'etudiant')->first();
        $roleId = $roleEtudiant ? $roleEtudiant->id : null;

        $user = Utilisateur::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->password),
            'email_verified_at' => now(), // Email automatiquement vérifié
            'role_id' => $roleId, // Assigner le rôle étudiant par défaut
        ]);

        // Événement de vérification email supprimé (vérification automatique à l'enregistrement)

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Handle an incoming API registration request.
     */
    public function api_store(Request $request)
    {
        // Normaliser les données
        $request->merge([
            'nom' => $request->input('nom'),
            'email' => strtolower($request->input('email', '')),
        ]);

        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Récupérer le rôle étudiant par défaut
        $roleEtudiant = Role::where('slug', 'etudiant')->first();
        $roleId = $roleEtudiant ? $roleEtudiant->id : null;

        $user = Utilisateur::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->password),
            'email_verified_at' => now(), // Email auto-vérifié
            'role_id' => $roleId, // Assigner le rôle étudiant par défaut
        ]);

        // Événement de vérification email supprimé

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
