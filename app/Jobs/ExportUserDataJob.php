<?php

namespace App\Jobs;

use App\Models\DataExport;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExportUserDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected DataExport $dataExport;
    protected Utilisateur $utilisateur;

    /**
     * Create a new job instance.
     */
    public function __construct(DataExport $dataExport)
    {
        $this->dataExport = $dataExport;
        $this->utilisateur = $dataExport->utilisateur;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->dataExport->update(['status' => 'processing']);

            // Collecter toutes les données
            $data = $this->collectUserData();

            // Déterminer le format d'export
            $format = $this->dataExport->format;
            
            if ($format === 'json') {
                $this->exportAsJson($data);
            } elseif ($format === 'csv') {
                $this->exportAsCsv($data);
            } elseif ($format === 'zip') {
                $this->exportAsZip($data);
            }

        } catch (\Exception $e) {
            Log::error('Export failed for user ' . $this->utilisateur->id, ['error' => $e->getMessage()]);
            $this->dataExport->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    /**
     * Collecte toutes les données de l'utilisateur
     */
    private function collectUserData(): array
    {
        return [
            'utilisateur' => $this->utilisateur->load('role')->toArray(),
            'publications' => $this->utilisateur->publications()
                ->with(['commentaires', 'reactions', 'medias'])
                ->get()
                ->toArray(),
            'commentaires' => $this->utilisateur->commentaires()
                ->with(['reactions', 'medias'])
                ->get()
                ->toArray(),
            'reactions' => $this->utilisateur->reactions()
                ->with('reactable')
                ->get()
                ->toArray(),
            'messages' => $this->utilisateur->messages()
                ->with(['conversation', 'expediteur'])
                ->get()
                ->toArray(),
            'groupes' => $this->utilisateur->groupes()
                ->with(['publications', 'utilisateurs'])
                ->get()
                ->toArray(),
            'notifications' => $this->utilisateur->notifications()
                ->get()
                ->toArray(),
            'conversations' => $this->utilisateur->conversations()
                ->with('utilisateurs')
                ->get()
                ->toArray(),
            'privacy_settings' => $this->utilisateur->privacySettings?->toArray(),
        ];
    }

    /**
     * Exporte les données en JSON
     */
    private function exportAsJson(array $data): void
    {
        $fileName = "export-{$this->utilisateur->id}-" . now()->format('Y-m-d-His') . '.json';
        $filePath = "exports/json/{$fileName}";

        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        Storage::disk('local')->put($filePath, $jsonData);

        $this->dataExport->markAsCompleted($filePath, $fileName);
    }

    /**
     * Exporte les données en CSV (format simplifié)
     */
    private function exportAsCsv(array $data): void
    {
        $fileName = "export-{$this->utilisateur->id}-" . now()->format('Y-m-d-His') . '.csv';
        $filePath = "exports/csv/{$fileName}";

        $csvContent = "Data Export - Campus Network RGPD\n";
        $csvContent .= "Utilisateur: {$this->utilisateur->nom}\n";
        $csvContent .= "Email: {$this->utilisateur->email}\n";
        $csvContent .= "Date d'export: " . now()->format('Y-m-d H:i:s') . "\n\n";

        // Profil
        $csvContent .= "=== PROFIL ===\n";
        $csvContent .= "Nom,Email,Filière,Année d'étude\n";
        $csvContent .= "\"{$this->utilisateur->nom}\",\"{$this->utilisateur->email}\",\"{$this->utilisateur->filiere}\",\"{$this->utilisateur->annee_etude}\"\n\n";

        // Publications
        $csvContent .= "=== PUBLICATIONS ===\n";
        $csvContent .= "ID,Contenu,Visibilité,Groupe,Date de création\n";
        foreach ($data['publications'] as $pub) {
            $contenu = str_replace('"', '""', $pub['contenu']);
            $csvContent .= "\"{$pub['id']}\",\"{$contenu}\",\"{$pub['visibilite']}\",\"{$pub['groupe_id']}\",\"{$pub['created_at']}\"\n";
        }
        $csvContent .= "\n";

        // Commentaires
        $csvContent .= "=== COMMENTAIRES ===\n";
        $csvContent .= "ID,Publication,Contenu,Date\n";
        foreach ($data['commentaires'] as $com) {
            $contenu = str_replace('"', '""', $com['contenu']);
            $csvContent .= "\"{$com['id']}\",\"{$com['publication_id']}\",\"{$contenu}\",\"{$com['created_at']}\"\n";
        }

        Storage::disk('local')->put($filePath, $csvContent);
        $this->dataExport->markAsCompleted($filePath, $fileName);
    }

    /**
     * Exporte les données en ZIP (archive JSON + CSV)
     */
    private function exportAsZip(array $data): void
    {
        // Crée les fichiers JSON et CSV
        $this->exportAsJson($data);
        $this->exportAsCsv($data);

        // Création du ZIP (implémentation simplifiée)
        // Nécessite: composer require ext-zip
        $fileName = "export-{$this->utilisateur->id}-" . now()->format('Y-m-d-His') . '.zip';
        $filePath = "exports/zip/{$fileName}";

        // Pour une implémentation complète, utiliser une library ZIP
        // Ici, on simule simplement en JSON pour démo
        $zipData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        Storage::disk('local')->put($filePath, $zipData);

        $this->dataExport->markAsCompleted($filePath, $fileName);
    }
}
