<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupeRequest;
use App\Http\Resources\GroupeResource;
use App\Models\Groupe;
use Illuminate\Http\Request;

// Contrôleur pour gérer les groupes : CRUD, adhésion, gestion
class GroupeController extends Controller
{
    // Liste paginée des groupes
    public function index(Request $request)
    {
        $query = Groupe::withCount('membres', 'publications')->latest();
        $p = $query->paginate(20);
        return GroupeResource::collection($p);
    }

    // Création d'un groupe
    public function store(StoreGroupeRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();
        $data['admin_id'] = $user->id;
        $groupe = Groupe::create($data);

        // Ajoute l'utilisateur comme membre admin dans le pivot
        $groupe->membres()->attach($user->id, ['role' => 'admin', 'rejoins_at' => now()]);

        $groupe->load('admin');

        return new GroupeResource($groupe);
    }

    // Détails d'un groupe
    public function show($id)
    {
        $groupe = Groupe::with(['admin', 'publications' => function ($q) { $q->latest()->limit(5); }])->findOrFail($id);
        return new GroupeResource($groupe);
    }

    // Mise à jour du groupe (seul l'admin peut)
    public function update(StoreGroupeRequest $request, $id)
    {
        $groupe = Groupe::findOrFail($id);
        $user = $request->user();

        if ($groupe->admin_id !== $user->id && !$user->estAdmin()) {
            return response()->json(['message' => 'Non autorisé à modifier ce groupe.'], 403);
        }

        $groupe->update($request->validated());
        $groupe->load('admin');

        return new GroupeResource($groupe);
    }

    // Suppression / archivage du groupe
    public function destroy(Request $request, $id)
    {
        $groupe = Groupe::findOrFail($id);
        $user = $request->user();

        if ($groupe->admin_id !== $user->id && !$user->estAdmin()) {
            return response()->json(['message' => 'Non autorisé à supprimer ce groupe.'], 403);
        }

        $groupe->delete();
        return response()->json(['message' => 'Groupe supprimé.']);
    }

    // Rejoindre un groupe
    public function join(Request $request, $id)
    {
        $groupe = Groupe::findOrFail($id);
        $user = $request->user();

        if ($groupe->membres()->where('utilisateur_id', $user->id)->exists()) {
            return response()->json(['message' => 'Vous êtes déjà membre.'], 400);
        }

        // Pour simplifier, on accepte immédiatement pour groupes publics/fermés, privé nécessiterait approbation
        $role = 'membre';
        $groupe->membres()->attach($user->id, ['role' => $role, 'rejoins_at' => now()]);

        return response()->json(['message' => 'Vous avez rejoint le groupe.']);
    }

    // Quitter un groupe
    public function leave(Request $request, $id)
    {
        $groupe = Groupe::findOrFail($id);
        $user = $request->user();

        $groupe->membres()->detach($user->id);

        return response()->json(['message' => 'Vous avez quitté le groupe.']);
    }

    // Transférer l'administration du groupe
    public function transferAdmin(Request $request, $id)
    {
        $request->validate(['nouvel_admin_id' => 'required|exists:utilisateurs,id']);

        $groupe = Groupe::findOrFail($id);
        $user = $request->user();

        if ($groupe->admin_id !== $user->id && !$user->estAdmin()) {
            return response()->json(['message' => 'Non autorisé à transférer ce groupe.'], 403);
        }

        $nouvelAdminId = $request->input('nouvel_admin_id');

        // Met à jour l'admin
        $groupe->admin_id = $nouvelAdminId;
        $groupe->save();

        // Met à jour les pivots : nouvel admin = admin, ancien admin downgradé en membre
        $groupe->membres()->syncWithoutDetaching([$nouvelAdminId => ['role' => 'admin', 'rejoins_at' => now()]]);
        $groupe->membres()->updateExistingPivot($user->id, ['role' => 'membre']);

        return response()->json(['message' => 'Administration transférée.']);
    }
}
