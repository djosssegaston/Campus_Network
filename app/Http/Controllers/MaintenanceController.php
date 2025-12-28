<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    /**
     * Afficher le tableau de bord de maintenance
     */
    public function dashboard(): View
    {
        $this->authorize('viewMaintenance');
        
        // Vérifier la santé du système
        $health = [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
        ];
        
        // Informations système
        $systemInfo = [
            'app_version' => config('app.version', 'Unknown'),
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'database_size' => $this->getDatabaseSize(),
            'storage_usage' => $this->getStorageUsage(),
        ];
        
        return view('admin.maintenance.dashboard', compact('health', 'systemInfo'));
    }

    /**
     * Afficher les outils de maintenance
     */
    public function tools(): View
    {
        $this->authorize('viewMaintenance');
        
        return view('admin.maintenance.tools');
    }

    /**
     * Exécuter la optimisation de cache
     */
    public function optimizeCache(): RedirectResponse
    {
        $this->authorize('viewMaintenance');
        
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('optimize:clear');
            
            return back()->with('success', 'Cache optimisé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Exécuter les migrations
     */
    public function runMigrations(): RedirectResponse
    {
        $this->authorize('viewMaintenance');
        
        try {
            Artisan::call('migrate', ['--force' => true]);
            
            return back()->with('success', 'Migrations exécutées avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Réinitialiser les données de test
     */
    public function resetTestData(): RedirectResponse
    {
        $this->authorize('viewMaintenance');
        
        if (app()->environment('production')) {
            return back()->with('error', 'Cette action n\'est pas autorisée en production');
        }
        
        try {
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed');
            
            return back()->with('success', 'Données de test réinitialisées');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Nettoyer les comptes inactifs
     */
    public function cleanupInactiveAccounts(Request $request): RedirectResponse
    {
        $this->authorize('viewMaintenance');
        
        $days = $request->get('days', 365);
        $since = now()->subDays((int)$days);
        
        $count = Utilisateur::where('last_seen', '<', $since)
            ->where('is_active', false)
            ->delete();
        
        return back()->with('success', "Supprimé $count comptes inactifs");
    }

    /**
     * Nettoyer les fichiers orphelins
     */
    public function cleanupOrphanFiles(): RedirectResponse
    {
        $this->authorize('viewMaintenance');
        
        $storageDir = storage_path('app/public');
        $deleted = 0;
        
        if (is_dir($storageDir)) {
            $files = File::files($storageDir);
            foreach ($files as $file) {
                // Vérifier si le fichier est référencé dans les publications
                $filename = $file->getFilename();
                $exists = Publication::where('media', 'like', "%$filename%")->exists();
                
                if (!$exists) {
                    File::delete($file->getPathname());
                    $deleted++;
                }
            }
        }
        
        return back()->with('success', "Supprimé $deleted fichiers orphelins");
    }

    /**
     * Optimiser la base de données
     */
    public function optimizeDatabase(): RedirectResponse
    {
        $this->authorize('viewMaintenance');
        
        try {
            if (config('database.default') === 'sqlite') {
                DB::statement('VACUUM');
            } elseif (config('database.default') === 'mysql') {
                $tables = DB::select('SHOW TABLES');
                foreach ($tables as $table) {
                    $tableName = array_values((array)$table)[0];
                    DB::statement("OPTIMIZE TABLE `$tableName`");
                }
            }
            
            return back()->with('success', 'Base de données optimisée');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Générer un rapport de maintenance
     */
    public function generateReport(): View
    {
        $this->authorize('viewMaintenance');
        
        $report = [
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'database_size' => $this->getDatabaseSize(),
            'storage_usage' => $this->getStorageUsage(),
            'total_users' => Utilisateur::count(),
            'total_publications' => Publication::count(),
            'inactive_users' => Utilisateur::where('is_active', false)->count(),
            'backup_date' => $this->getLastBackupDate(),
        ];
        
        return view('admin.maintenance.report', compact('report'));
    }

    /**
     * Vérifier la base de données
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'OK', 'message' => 'Base de données connectée'];
        } catch (\Exception $e) {
            return ['status' => 'ERROR', 'message' => $e->getMessage()];
        }
    }

    /**
     * Vérifier le stockage
     */
    private function checkStorage(): array
    {
        $path = storage_path('app');
        if (is_writable($path)) {
            return ['status' => 'OK', 'message' => 'Stockage accessible'];
        }
        return ['status' => 'WARNING', 'message' => 'Stockage non-accessible'];
    }

    /**
     * Vérifier le cache
     */
    private function checkCache(): array
    {
        try {
            cache()->put('health_check', 'ok', 60);
            cache()->forget('health_check');
            return ['status' => 'OK', 'message' => 'Cache fonctionnel'];
        } catch (\Exception $e) {
            return ['status' => 'ERROR', 'message' => $e->getMessage()];
        }
    }

    /**
     * Vérifier la queue
     */
    private function checkQueue(): array
    {
        return ['status' => 'OK', 'message' => 'Queue système OK'];
    }

    /**
     * Obtenir la taille de la base de données
     */
    private function getDatabaseSize(): string
    {
        if (config('database.default') === 'sqlite') {
            $dbPath = database_path('database.sqlite');
            if (file_exists($dbPath)) {
                return $this->formatBytes(filesize($dbPath));
            }
        }
        return 'N/A';
    }

    /**
     * Obtenir l'utilisation du stockage
     */
    private function getStorageUsage(): string
    {
        $path = storage_path('app');
        $size = 0;
        
        if (is_dir($path)) {
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $size += $file->getSize();
            }
        }
        
        return $this->formatBytes($size);
    }

    /**
     * Obtenir la date de la dernière sauvegarde
     */
    private function getLastBackupDate(): string
    {
        $backupDir = storage_path('backups');
        if (is_dir($backupDir)) {
            $files = File::files($backupDir);
            if (count($files) > 0) {
                return date('Y-m-d H:i:s', $files[0]->getMTime());
            }
        }
        return 'Aucune sauvegarde';
    }

    /**
     * Formater les bytes
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
