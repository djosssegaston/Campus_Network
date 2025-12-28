<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicationViewController;
use App\Http\Controllers\GroupeViewController;
use App\Http\Controllers\GroupeMessageController;
use App\Http\Controllers\GroupePublicationController;
use App\Http\Controllers\GroupeSettingController;
use App\Http\Controllers\GroupeMembreController;
use App\Http\Controllers\MessageViewController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\PublicationPartageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminViewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PrivacySettingController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

/* ============================================================
   PUBLIC ROUTES - Accessible à tous
   ============================================================ */

Route::get('/', function () {
    return view('welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

/* ============================================================
   AUTHENTICATION ROUTES - Importé depuis auth.php
   Inclut : login, register, password reset, email verification
   ============================================================ */

require __DIR__.'/auth.php';

/* ============================================================
   AUTHENTICATED ROUTES - Nécessitent auth
   ============================================================ */

Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Feed et Publications
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    Route::get('/publications/create', [PublicationController::class, 'create'])->name('publications.create');
    Route::post('/publications', [PublicationController::class, 'store'])->name('publications.store');
    Route::get('/publications/{publication}', [PublicationController::class, 'show'])->name('publications.show');
    Route::delete('/publications/{publication}', [PublicationController::class, 'destroy'])->name('publications.destroy');

    // Commentaires
    Route::post('/publications/{publication}/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');
    Route::delete('/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy');

    // Reactions (Likes)
    Route::post('/publications/{publication}/reactions', [ReactionController::class, 'store'])->name('reactions.store');
    Route::delete('/reactions/{reaction}', [ReactionController::class, 'destroy'])->name('reactions.destroy');

    // Partages de publications
    Route::post('/publications/{publication}/partages', [PublicationPartageController::class, 'store'])->name('partages.store');
    Route::delete('/partages/{partage}', [PublicationPartageController::class, 'destroy'])->name('partages.destroy');

    // Groupes
    Route::prefix('groupes')->group(function () {
        Route::get('/', [GroupeViewController::class, 'index'])->name('groupes.index');
        Route::get('/create', [GroupeViewController::class, 'create'])->name('groupes.create');
        Route::post('/', [GroupeViewController::class, 'store'])->name('groupes.store');
        Route::get('/{groupe}', [GroupeViewController::class, 'show'])->name('groupes.show');
        
        // Adhésion au groupe
        Route::post('/{groupe}/join', [GroupeMembreController::class, 'join'])->name('groupes.join');
        Route::post('/{groupe}/leave', [GroupeMembreController::class, 'leave'])->name('groupes.leave');
        
        // Messages du groupe
        Route::post('/{groupe}/messages', [GroupeMessageController::class, 'store'])->name('groupe-messages.store');
        Route::delete('/{groupe}/messages/{message}', [GroupeMessageController::class, 'destroy'])->name('groupe-messages.destroy');
        
        // Publications du groupe
        Route::post('/{groupe}/publications', [GroupePublicationController::class, 'store'])->name('groupe-publications.store');
        Route::put('/{groupe}/publications/{publication}', [GroupePublicationController::class, 'update'])->name('groupe-publications.update');
        Route::delete('/{groupe}/publications/{publication}', [GroupePublicationController::class, 'destroy'])->name('groupe-publications.destroy');
        
        // Paramètres du groupe (admin uniquement)
        Route::get('/{groupe}/settings', [GroupeSettingController::class, 'edit'])->name('groupe-settings.edit');
        Route::put('/{groupe}/settings', [GroupeSettingController::class, 'update'])->name('groupe-settings.update');
        Route::delete('/{groupe}', [GroupeSettingController::class, 'destroy'])->name('groupe-settings.destroy');
    });

    // Messages
    Route::get('/messages', [MessageViewController::class, 'index'])->name('messages.index');
    Route::get('/messages/new', [MessageViewController::class, 'create'])->name('messages.create.view');
    Route::post('/messages/new/{user}', [MessageViewController::class, 'store'])->name('messages.create');
    Route::get('/messages/{conversation}', [MessageViewController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.deleteAllRead');

    // Admin Routes - Protégées par middleware 'is_admin'
    Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
        // Gestion des utilisateurs
        Route::resource('users', UserManagementController::class);
        Route::post('users/{user}/restore', [UserManagementController::class, 'restore'])->name('users.restore');
        Route::post('users/{user}/change-role', [UserManagementController::class, 'changeRole'])->name('users.changeRole');

        // Tableau de bord admin
        Route::get('/dashboard', [AdminViewController::class, 'dashboard'])->name('dashboard');

        // Gestion des groupes (si applicable)
        Route::resource('groupes', GroupManagementController::class);

        // Gestion des publications (si applicable)
        Route::resource('publications', PublicationManagementController::class);
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        // Privacy Settings
        Route::get('/privacy', [PrivacySettingController::class, 'index'])->name('privacy-settings.index');
        Route::patch('/privacy', [PrivacySettingController::class, 'update'])->name('privacy-settings.update');
        
        // Data Exports
        Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
        Route::post('/exports', [ExportController::class, 'store'])->name('exports.store');
        Route::get('/exports/{dataExport}/download', [ExportController::class, 'download'])->name('exports.download');
        Route::delete('/exports/{dataExport}', [ExportController::class, 'destroy'])->name('exports.destroy');
    });

    // Routes Conversations - Authentifiées
    Route::middleware('auth')->group(function () {
        Route::resource('conversations', ConversationController::class);
        Route::post('conversations/{conversation}/messages', [ConversationController::class, 'addMessage'])->name('conversations.addMessage');
    });
});

/* ============================================================
   MEDIA SERVING ROUTE - Servir les fichiers de storage
   ============================================================ */

Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404, 'Fichier non trouvé');
    }
    
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.serve');

