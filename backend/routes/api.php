<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Imports des controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\GroupeController;

/*
 * =========================================
 * API V1 Routes
 * =========================================
 * Préfixe: /api/v1
 */

Route::prefix('v1')->group(function () {

    /* =====================================
       PUBLIC ROUTES (NO AUTH REQUIRED)
       ===================================== */

    // Authentication routes
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

    // Public publications & feed
    Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');
    Route::get('/publications/{id}', [PublicationController::class, 'show'])->name('publications.show');

    // Public groups listing
    Route::get('/groupes', [GroupeController::class, 'index'])->name('groupes.index');
    Route::get('/groupes/{groupe}', [GroupeController::class, 'show'])->name('groupes.show');

    /* =====================================
       AUTHENTICATED ROUTES
       Middleware: auth:sanctum
       ===================================== */

    Route::middleware('auth:sanctum')->group(function () {

        // === Auth Routes ===
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::get('/me', [AuthController::class, 'me'])->name('auth.me');

        // === Publications Routes ===
        Route::post('/publications', [PublicationController::class, 'store'])->name('publications.store');
        Route::put('/publications/{id}', [PublicationController::class, 'update'])->name('publications.update');
        Route::delete('/publications/{id}', [PublicationController::class, 'destroy'])->name('publications.destroy');
        Route::get('/publications/feed', [PublicationController::class, 'feed'])->name('publications.feed');

        // === Commentaires Routes ===
        Route::post('/publications/{publication}/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');
        Route::put('/commentaires/{commentaire}', [CommentaireController::class, 'update'])->name('commentaires.update');
        Route::delete('/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy');

        // === Reactions Routes ===
        Route::prefix('/publications/{publication}/reactions')->group(function () {
            Route::post('/', [ReactionController::class, 'storePublication'])->name('reactions.publication.store');
            Route::delete('/', [ReactionController::class, 'destroyPublication'])->name('reactions.publication.destroy');
        });

        Route::prefix('/commentaires/{commentaire}/reactions')->group(function () {
            Route::post('/', [ReactionController::class, 'storeCommentaire'])->name('reactions.commentaire.store');
            Route::delete('/', [ReactionController::class, 'destroyCommentaire'])->name('reactions.commentaire.destroy');
        });

        // === Groupes Routes ===
        Route::post('/groupes', [GroupeController::class, 'store'])->name('groupes.store');
        Route::put('/groupes/{groupe}', [GroupeController::class, 'update'])->name('groupes.update');
        Route::delete('/groupes/{groupe}', [GroupeController::class, 'destroy'])->name('groupes.destroy');
        Route::post('/groupes/{groupe}/join', [GroupeController::class, 'join'])->name('groupes.join');
        Route::post('/groupes/{groupe}/leave', [GroupeController::class, 'leave'])->name('groupes.leave');
        Route::post('/groupes/{groupe}/transfer-admin', [GroupeController::class, 'transferAdmin'])->name('groupes.transferAdmin');

    });

    /* =====================================
       ADMIN ROUTES
       Middleware: auth:sanctum + admin check
       ===================================== */

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/admin/stats', function (Request $request) {
            if (!$request->user()?->estAdmin()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return (new \App\Http\Controllers\Api\AdminController())->stats();
        })->name('admin.stats');

        Route::get('/admin/users', function (Request $request) {
            if (!$request->user()?->estAdmin()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return (new \App\Http\Controllers\Api\AdminController())->users();
        })->name('admin.users');

        Route::get('/admin/publications/pending', function (Request $request) {
            if (!$request->user()?->estAdmin()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return (new \App\Http\Controllers\Api\AdminController())->publicationsPending();
        })->name('admin.publications.pending');
    });

});
