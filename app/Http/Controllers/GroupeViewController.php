<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Http\Requests\StoreGroupeRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GroupeViewController extends Controller
{
    /**
     * Display all groups.
     */
    public function index(): View
    {
        $groupes = Groupe::paginate(12);

        return view('groupes.index', [
            'groupes' => $groupes
        ]);
    }

    /**
     * Display the create group form.
     */
    public function create(): View
    {
        return view('groupes.create');
    }

    /**
     * Store a newly created group.
     */
    public function store(StoreGroupeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $groupe = Groupe::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'visibilite' => $validated['visibilite'] ?? 'public',
            'admin_id' => auth()->id(),
        ]);

        // Ajouter le créateur comme membre admin
        $groupe->utilisateurs()->attach(auth()->id(), ['role' => 'admin']);

        return redirect()->route('groupes.show', $groupe->id)->with('success', 'Groupe créé avec succès!');
    }

    /**
     * Display a specific group.
     */
    public function show($id): View
    {
        $groupe = Groupe::with('utilisateurs')->findOrFail($id);
        $publications = $groupe->publications()->with('utilisateur')->paginate(10);
        $messages = $groupe->messages()->with('utilisateur')->latest()->paginate(20);

        return view('groupes.show', [
            'groupe' => $groupe,
            'publications' => $publications,
            'messages' => $messages,
            'groupeId' => $id
        ]);
    }
}
