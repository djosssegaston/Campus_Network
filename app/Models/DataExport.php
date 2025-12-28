<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * Modèle DataExport pour les exports de données RGPD
 */
class DataExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'format',
        'status',
        'file_path',
        'file_name',
        'total_items',
        'processed_items',
        'error_message',
        'expires_at',
        'downloaded_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'downloaded_at' => 'datetime',
    ];

    /**
     * Relation vers l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    /**
     * Vérifie si l'export a expiré
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Vérifie si l'export est téléchargeable
     */
    public function isDownloadable(): bool
    {
        return $this->status === 'completed' && !$this->isExpired() && $this->file_path;
    }

    /**
     * Obtient le pourcentage de progression
     */
    public function getProgress(): int
    {
        if ($this->total_items === 0) {
            return 0;
        }
        return (int) (($this->processed_items / $this->total_items) * 100);
    }

    /**
     * Définit l'export comme complété
     */
    public function markAsCompleted(string $filePath, string $fileName): void
    {
        $this->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'file_name' => $fileName,
            'expires_at' => Carbon::now()->addDays(32),
        ]);
    }

    /**
     * Marque comme échec
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
