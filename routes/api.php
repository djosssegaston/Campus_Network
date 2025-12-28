<?php

use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\GroupeController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\PrivacySettingController;
use App\Http\Controllers\Api\ExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/* ============================================================
   AUTHENTICATION ROUTES (PUBLIC)
   ============================================================ */

Route::post('/v1/auth/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
Route::post('/v1/auth/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);

/* ============================================================
   PUBLIC API ROUTES (Lectures seules)
   ============================================================ */

// Publications - Lectures publiques
Route::get('/v1/publications', [PublicationController::class, 'index']);
Route::get('/v1/publications/{id}', [PublicationController::class, 'show']);

// Groupes - Lectures publiques
Route::get('/v1/groupes', [GroupeController::class, 'index']);
Route::get('/v1/groupes/{id}', [GroupeController::class, 'show']);
Route::get('/v1/groupes/{id}/publications', [GroupeController::class, 'publications']);

/* ============================================================
   AUTHENTICATED API ROUTES
   ============================================================ */

Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/v1/auth/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);
    Route::post('/v1/auth/logout-all', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logoutAll']);
    Route::get('/v1/auth/me', [\App\Http\Controllers\Api\Auth\AuthController::class, 'me']);
    Route::post('/v1/publications', [PublicationController::class, 'store']);
    Route::put('/v1/publications/{id}', [PublicationController::class, 'update']);
    Route::delete('/v1/publications/{id}', [PublicationController::class, 'destroy']);
    
    // Commentaires
    Route::get('/v1/publications/{id}/commentaires', [CommentaireController::class, 'index']);
    Route::post('/v1/publications/{id}/commentaires', [CommentaireController::class, 'store']);
    Route::delete('/v1/commentaires/{id}', [CommentaireController::class, 'destroy']);
    
    // Réactions
    Route::post('/v1/publications/{id}/reactions', [ReactionController::class, 'store']);
    Route::delete('/v1/reactions/{id}', [ReactionController::class, 'destroy']);
    Route::get('/v1/publications/{id}/reactions', [ReactionController::class, 'index']);
    
    // Groupes
    Route::post('/v1/groupes', [GroupeController::class, 'store']);
    Route::put('/v1/groupes/{id}', [GroupeController::class, 'update']);
    Route::delete('/v1/groupes/{id}', [GroupeController::class, 'destroy']);
    Route::post('/v1/groupes/{id}/join', [GroupeController::class, 'join']);
    Route::post('/v1/groupes/{id}/leave', [GroupeController::class, 'leave']);
    
    // Messages & Conversations
    Route::get('/v1/conversations', [MessageController::class, 'conversations']);
    Route::get('/v1/conversations/{id}', [MessageController::class, 'show']);
    Route::post('/v1/conversations', [MessageController::class, 'createConversation']);
    Route::post('/v1/conversations/{id}/messages', [MessageController::class, 'store']);
    Route::get('/v1/messages/{id}', [MessageController::class, 'getMessage']);
    Route::delete('/v1/messages/{id}', [MessageController::class, 'destroy']);
    
    // Notifications
    Route::get('/v1/notifications', [NotificationController::class, 'index']);
    Route::patch('/v1/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/v1/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    
    // Search
    Route::get('/v1/search', [SearchController::class, 'search']);
    Route::get('/v1/search/suggestions', [SearchController::class, 'suggestions']);
    
    // Privacy Settings
    Route::get('/v1/privacy-settings', [PrivacySettingController::class, 'show']);
    Route::patch('/v1/privacy-settings', [PrivacySettingController::class, 'update']);
    
    // Data Exports (RGPD)
    Route::get('/v1/exports', [ExportController::class, 'index']);
    Route::post('/v1/exports', [ExportController::class, 'store']);
    Route::get('/v1/exports/{export}', [ExportController::class, 'show']);
    Route::delete('/v1/exports/{export}', [ExportController::class, 'destroy']);
    
    // Admin Routes
    Route::middleware('admin')->prefix('v1/admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/{id}', [AdminController::class, 'userDetail']);
        Route::put('/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::get('/publications', [AdminController::class, 'publications']);
        Route::get('/signalements', [AdminController::class, 'signalements']);
    });
});
