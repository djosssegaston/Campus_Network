<?php

namespace App\Http\Controllers;

use App\Models\DataExport;
use App\Jobs\ExportUserDataJob;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    /**
     * Affiche la page des exports de données
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $exports = $user->dataExports()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('profile.exports', [
            'exports' => $exports,
        ]);
    }

    /**
     * Crée une nouvelle demande d'export
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'format' => ['required', 'in:json,csv,zip'],
        ]);

        $user = $request->user();

        // Vérifier s'il y a déjà une demande en cours
        $existingExport = $user->dataExports()
            ->where('status', 'processing')
            ->first();

        if ($existingExport) {
            return redirect()->route('exports.index')
                ->with('error', 'Un export est déjà en cours de traitement. Veuillez attendre sa complétion.');
        }

        // Créer une nouvelle demande d'export
        $export = $user->dataExports()->create([
            'format' => $request->input('format'),
            'status' => 'pending',
            'total_items' => $this->estimateItemCount($user),
        ]);

        // Lancer le job
        ExportUserDataJob::dispatch($export);

        return redirect()->route('exports.index')
            ->with('success', 'Votre demande d\'export a été créée. Elle sera traitée dans les prochaines minutes.');
    }

    /**
     * Télécharge un export complété
     */
    public function download(DataExport $export)
    {
        $request = request();
        
        // Vérifier les permissions
        if ($export->utilisateur_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        // Vérifier que l'export est téléchargeable
        if (!$export->isDownloadable()) {
            abort(404, 'Export not available or expired');
        }

        // Marquer comme téléchargé
        $export->update(['downloaded_at' => now()]);

        // Retourner le fichier
        return response()->download(Storage::disk('local')->path($export->file_path), $export->file_name);
    }

    /**
     * Supprime un export
     */
    public function destroy(DataExport $export): RedirectResponse
    {
        $request = request();

        // Vérifier les permissions
        if ($export->utilisateur_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        // Supprimer le fichier
        if ($export->file_path && Storage::disk('local')->exists($export->file_path)) {
            Storage::disk('local')->delete($export->file_path);
        }

        // Supprimer l'enregistrement
        $export->delete();

        return redirect()->route('exports.index')
            ->with('success', 'L\'export a été supprimé.');
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
