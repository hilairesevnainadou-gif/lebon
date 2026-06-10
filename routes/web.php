<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Routes publiques (sans authentification) ──────────────────
Route::get('/vehicule/ad',               [AdController::class, 'publicShow'])->name('ads.public');
Route::get('/vehicule/favoris',          [AdController::class, 'favorites'])->name('ads.favorites');
Route::get('/annonces/{ad}/reserver',                           [AdController::class, 'reserve'])->name('ads.reserve');
Route::get('/annonces/{ad}/reserver/formulaire',                [AdController::class, 'reserveForm'])->name('ads.reserve.form');
Route::post('/annonces/{ad}/reserver',                          [AdController::class, 'storeReservation'])->name('ads.reserve.store');
Route::get('/annonces/{ad}/reservation/{reservation}/confirmee',[AdController::class, 'reservationConfirmed'])->name('ads.reserve.confirmed');
Route::post('/annonces/{ad}/like',                             [AdController::class, 'toggleLike'])->name('ads.like');

// ── Auth ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Mot de passe oublié
    Route::get('/mot-de-passe-oublie',  [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reinitialiser/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reinitialiser',        [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Zone authentifiée ─────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/annonces',              [AdController::class, 'index'])->name('ads.index');
    Route::get('/annonces/creer',        [AdController::class, 'create'])->name('ads.create');
    Route::post('/annonces',             [AdController::class, 'store'])->name('ads.store');

    // Brouillons (avant {ad} pour éviter le conflit de route)
    Route::post('/annonces/brouillon',              [AdController::class, 'saveDraft'])->name('ads.draft');
    Route::get('/annonces/brouillons',              [AdController::class, 'getDrafts'])->name('ads.drafts');
    Route::get('/annonces/brouillon/{id}/reprendre',[AdController::class, 'resumeDraft'])->name('ads.draft.resume');
    Route::delete('/annonces/brouillon/{id}',       [AdController::class, 'deleteDraft'])->name('ads.draft.delete');

    Route::get('/annonces/{ad}',                      [AdController::class, 'show'])->name('ads.show');
    Route::get('/annonces/{ad}/editer',               [AdController::class, 'edit'])->name('ads.edit');
    Route::put('/annonces/{ad}',                      [AdController::class, 'update'])->name('ads.update');
    Route::delete('/annonces/{ad}/photos/{photo}',    [AdController::class, 'destroyPhoto'])->name('ads.photos.destroy');
    Route::post('/annonces/{ad}/photos/reorder',      [AdController::class, 'reorderPhotos'])->name('ads.photos.reorder');
    Route::get('/annonces/{ad}/partager',             [AdController::class, 'share'])->name('ads.share');
    Route::patch('/annonces/{ad}/statut',             [AdController::class, 'toggleStatus'])->name('ads.toggle-status');

    // Gestion des utilisateurs (admin uniquement)
    Route::middleware('admin')->group(function () {
        Route::get('/utilisateurs',                [UserController::class, 'index'])->name('users.index');
        Route::get('/utilisateurs/creer',          [UserController::class, 'create'])->name('users.create');
        Route::post('/utilisateurs',               [UserController::class, 'store'])->name('users.store');
        Route::get('/utilisateurs/{user}/editer',  [UserController::class, 'edit'])->name('users.edit');
        Route::put('/utilisateurs/{user}',         [UserController::class, 'update'])->name('users.update');
        Route::delete('/utilisateurs/{user}',      [UserController::class, 'destroy'])->name('users.destroy');
    });
});
