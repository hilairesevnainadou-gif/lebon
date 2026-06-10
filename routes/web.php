<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Activation de compte (invitation admin) ───────────────────
Route::get('/activation/{token}',  [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/activation/{token}', [InvitationController::class, 'activate'])->name('invitation.activate');

// ── Routes publiques (sans authentification) ──────────────────
Route::get('/vehicule/ad',               [AdController::class, 'publicShow'])->name('ads.public');
Route::get('/vehicule/favoris',          [AdController::class, 'favorites'])->name('ads.favorites');
Route::get('/annonces/{ad}/reserver',                           [AdController::class, 'reserve'])->name('ads.reserve');
Route::get('/annonces/{ad}/reserver/formulaire',                [AdController::class, 'reserveForm'])->name('ads.reserve.form');
Route::get('/annonces/{ad}/reserver/recap',                     [AdController::class, 'reserveRecap'])->name('ads.reserve.recap');
Route::get('/annonces/{ad}/reserver/virement',                  [AdController::class, 'reserveVirement'])->name('ads.reserve.virement');
Route::post('/annonces/{ad}/reserver/virement-declare',         [AdController::class, 'storeVirementDeclaration'])->name('ads.reserve.virement.declare');
Route::post('/annonces/{ad}/reserver',                          [AdController::class, 'storeReservation'])->name('ads.reserve.store');
Route::get('/annonces/{ad}/reservation/{reservation}/confirmee',[AdController::class, 'reservationConfirmed'])->name('ads.reserve.confirmed');
Route::post('/annonces/{ad}/like',                             [AdController::class, 'toggleLike'])->name('ads.like');

// ── Auth ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/a3f7k',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/a3f7k', [AuthController::class, 'login'])->name('login.post');

    // Mot de passe oublié
    Route::get('/c4x8p',         [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/c4x8p',        [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/c4x8p/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/c4x8p/save',   [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});
Route::post('/b9m2r', [AuthController::class, 'logout'])->name('logout');

// ── Zone authentifiée ─────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/d6t1z',              [AdController::class, 'index'])->name('ads.index');
    Route::get('/d6t1z/creer',        [AdController::class, 'create'])->name('ads.create');
    Route::post('/d6t1z',             [AdController::class, 'store'])->name('ads.store');

    // Brouillons (avant {ad} pour éviter le conflit de route)
    Route::post('/d6t1z/brouillon',              [AdController::class, 'saveDraft'])->name('ads.draft');
    Route::get('/d6t1z/brouillons',              [AdController::class, 'getDrafts'])->name('ads.drafts');
    Route::get('/d6t1z/brouillon/{id}/reprendre',[AdController::class, 'resumeDraft'])->name('ads.draft.resume');
    Route::delete('/d6t1z/brouillon/{id}',       [AdController::class, 'deleteDraft'])->name('ads.draft.delete');

    Route::get('/d6t1z/{ad}',                      [AdController::class, 'show'])->name('ads.show');
    Route::get('/d6t1z/{ad}/editer',               [AdController::class, 'edit'])->name('ads.edit');
    Route::put('/d6t1z/{ad}',                      [AdController::class, 'update'])->name('ads.update');
    Route::delete('/d6t1z/{ad}/photos/{photo}',    [AdController::class, 'destroyPhoto'])->name('ads.photos.destroy');
    Route::post('/d6t1z/{ad}/photos/reorder',      [AdController::class, 'reorderPhotos'])->name('ads.photos.reorder');
    Route::get('/d6t1z/{ad}/partager',             [AdController::class, 'share'])->name('ads.share');
    Route::patch('/d6t1z/{ad}/statut',             [AdController::class, 'toggleStatus'])->name('ads.toggle-status');

    // Gestion des utilisateurs (admin uniquement)
    Route::middleware('admin')->group(function () {
        Route::get('/e5w9n',                [UserController::class, 'index'])->name('users.index');
        Route::get('/e5w9n/creer',          [UserController::class, 'create'])->name('users.create');
        Route::post('/e5w9n',               [UserController::class, 'store'])->name('users.store');
        Route::get('/e5w9n/{user}/editer',  [UserController::class, 'edit'])->name('users.edit');
        Route::put('/e5w9n/{user}',         [UserController::class, 'update'])->name('users.update');
        Route::delete('/e5w9n/{user}',      [UserController::class, 'destroy'])->name('users.destroy');
    });
});
