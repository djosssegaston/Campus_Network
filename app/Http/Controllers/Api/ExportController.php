<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\AuthenticatedUser;
use App\Models\DataExport;
use App\Jobs\ExportUserDataJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    use AuthenticatedUser;

    /**
     * Liste les exports de l'utilisateur actuel
     */
    public function index(): JsonResponse
    {
        $user = $this->user();
        $exports = $user->dataExports()
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($exports);
    }

    /**
     * Crée une nouvelle demande d'export
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'format' => ['required', 'in:json,csv,zip'],
        ]);

        $user = $this->user();

        // Vérifier s'il y a déjà une demande en cours
        $existingExport = $user->dataExports()
            ->where('status', 'processing')
            ->first();

        if ($existingExport) {
            return response()->json([
                'message' => 'Un export est déjà en cours de traitement',
                'existing_export' => $existingExport,
            ], 409);
        }

        // Créer une nouvelle demande d'export
        $export = $user->dataExports()->create([
            'format' => $request->input('format'),
            'status' => 'pending',
            'total_items' => $this->estimateItemCount($user),
        ]);

        // Lancer le job
        ExportUserDataJob::dispatch($export);

        return response()->json([
            'message' => 'Demande d\'export créée',
            'data' => $export,
        ], 201);
    }

    /**
     * Récupère les détails d'un export
     */
    public function show(DataExport $export): JsonResponse
    {
        $user = $this->user();

        if ($export->utilisateur_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => $export,
        ]);
    }

    /**
     * Supprime un export
     */
    public function destroy(DataExport $export): JsonResponse
    {
        $user = $this->user();

        if ($export->utilisateur_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Supprimer le fichier
        if ($export->file_path && Storage::disk('local')->exists($export->file_path)) {
            Storage::disk('local')->delete($export->file_path);
        }

        // Supprimer l'enregistrement
        $export->delete();

        return response()->json([
            'message' => 'Export supprimé',
        ]);
    }

    /**
     * Estime le nombre d'items à exporter
     */
    private function estimateItemCount($user): int
    {
        $count = 0;
        $count += $user->publications()->count();
        $count += $user->commentaires()->count();
        $count += $user->reactions()->count();
        $count += $user->messages()->count();
        $count += 1; // Pour le profil
        return $count;
    }
}
