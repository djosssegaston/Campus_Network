<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SystemSettingController extends Controller
{
    /**
     * Afficher les paramètres système
     */
    public function index(): View
    {
        $this->authorize('viewSystemSettings');
        
        $settings = SystemSetting::all()->pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Mettre à jour les paramètres système
     */
    public function update(Request $request): RedirectResponse
    {
        $this->authorize('updateSystemSettings');

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string',
            'maintenance_mode' => 'boolean',
            'max_upload_size' => 'required|integer|min:1|max:1000',
            'max_users' => 'nullable|integer|min:-1',
            'require_email_verification' => 'boolean',
            'allow_user_registration' => 'boolean',
            'allow_group_creation' => 'boolean',
            'moderation_enabled' => 'boolean',
            'auto_delete_inactive_accounts' => 'integer|min:0',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('settings.index')->with('success', 'Paramètres système mis à jour avec succès');
    }

    /**
     * Afficher les logs système
     */
    public function logs(Request $request): View
    {
        $this->authorize('viewSystemSettings');
        
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $logs = array_reverse(explode("\n", $content));
            $logs = array_slice($logs, 0, 100);
        }
        
        return view('admin.settings.logs', compact('logs'));
    }

    /**
     * Effacer les logs
     */
    public function clearLogs(): RedirectResponse
    {
        $this->authorize('updateSystemSettings');
        
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        return back()->with('success', 'Logs effacés avec succès');
    }

    /**
     * Exécuter la maintenance
     */
    public function maintenance(): RedirectResponse
    {
        $this->authorize('updateSystemSettings');
        
        // Clear cache
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        
        return back()->with('success', 'Maintenance effectuée avec succès');
    }
}
