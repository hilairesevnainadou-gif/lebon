<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PcAdController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// ── Activation de compte (invitation admin) ───────────────────
Route::get('/activation/{token}',  [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/activation/{token}', [InvitationController::class, 'activate'])->name('invitation.activate');

// ── Fichiers du disque "public" servis via PHP ─────────────────
// Contourne les hébergeurs mutualisés (ex: LWS) qui bloquent au
// niveau Apache toute URL contenant "/storage/" (403 brut, avant
// même d'atteindre PHP — un .htaccess ne peut pas passer outre).
// D'où le préfixe "/media/" à la place de "/storage/".
Route::get('/media/{path}', function (string $path) {
    $disk = Storage::disk('public');

    abort_unless($disk->exists($path), 404);

    $fullPath = $disk->path($path);

    return response()->file($fullPath, [
        'Content-Type' => $disk->mimeType($path) ?? 'application/octet-stream',
    ]);
})->where('path', '.*')->name('storage.local');

// ── DIAGNOSTIC TEMPORAIRE — à supprimer une fois le problème résolu ──
Route::get('/mediacheck', function () {
    $disk = Storage::disk('public');
    $testPath = 'annonces/23/photos/12462c1b-6ee5-4c32-89ce-5e40df304c52.png';

    $lines = [
        'base_path()                => ' . base_path(),
        'storage_path()             => ' . storage_path(),
        'storage_path("app/public") => ' . storage_path('app/public'),
        'realpath(storage/app/public) => ' . (realpath(storage_path('app/public')) ?: '(realpath a échoué / inaccessible)'),
        'is_dir(storage/app/public) => ' . (is_dir(storage_path('app/public')) ? 'oui' : 'NON'),
        'is_readable(storage/app/public) => ' . (is_readable(storage_path('app/public')) ? 'oui' : 'NON'),
        'ini_get(open_basedir)      => ' . (ini_get('open_basedir') ?: '(vide, pas de restriction)'),
        '',
        'Chemin testé : ' . $testPath,
        'Storage::disk(public)->exists() => ' . ($disk->exists($testPath) ? 'oui' : 'NON'),
        'Storage::disk(public)->path()   => ' . $disk->path($testPath),
        'file_exists() direct sur ce chemin => ' . (file_exists($disk->path($testPath)) ? 'oui' : 'NON'),
        '',
        'Contenu de storage/app/public/annonces/23/photos :',
    ];

    $photosDir = storage_path('app/public/annonces/23/photos');
    if (is_dir($photosDir)) {
        foreach (scandir($photosDir) as $f) {
            $lines[] = '  - ' . $f;
        }
    } else {
        $lines[] = '  (dossier introuvable ou inaccessible depuis PHP)';
    }

    return response('<pre>' . e(implode("\n", $lines)) . '</pre>');
})->name('mediacheck.debug');

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

    // Vérification OTP après mot de passe valide
    Route::get('/f2h6y',            [AuthController::class, 'showOtp'])->name('otp.show');
    Route::post('/f2h6y',           [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/f2h6y/renvoyer',  [AuthController::class, 'resendOtp'])->name('otp.resend');

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

    // Espace annonces PC (nécessite la permission menu.pc.view)
    // IMPORTANT : ce bloc doit rester déclaré avant les routes génériques
    // /d6t1z/{ad} ci-dessous, sinon "pc" est interprété comme un id d'annonce
    // (Laravel matche les routes dans l'ordre de déclaration) et pc.index
    // devient inatteignable (404 au lieu de la liste des annonces PC).
    Route::get('/d6t1z/pc',           [PcAdController::class, 'index'])->name('pc.index');
    Route::get('/d6t1z/pc/creer',     [PcAdController::class, 'create'])->name('pc.create');
    Route::post('/d6t1z/pc',          [PcAdController::class, 'store'])->name('pc.store');
    Route::get('/d6t1z/pc/{ad}',      [PcAdController::class, 'show'])->name('pc.show');

    Route::get('/d6t1z/{ad}',                      [AdController::class, 'show'])->name('ads.show');
    Route::get('/d6t1z/{ad}/editer',               [AdController::class, 'edit'])->name('ads.edit');
    Route::put('/d6t1z/{ad}',                      [AdController::class, 'update'])->name('ads.update');
    Route::delete('/d6t1z/{ad}/photos/{photo}',    [AdController::class, 'destroyPhoto'])->name('ads.photos.destroy');
    Route::post('/d6t1z/{ad}/photos/reorder',      [AdController::class, 'reorderPhotos'])->name('ads.photos.reorder');
    Route::get('/d6t1z/{ad}/partager',             [AdController::class, 'share'])->name('ads.share');
    Route::patch('/d6t1z/{ad}/statut',             [AdController::class, 'toggleStatus'])->name('ads.toggle-status');

    // Gestion des utilisateurs (admin uniquement)
    Route::middleware('admin')->group(function () {
        Route::get('/e5w9n/annonces',       [AdController::class, 'adminAll'])->name('admin.ads.index');
        Route::get('/e5w9n',                [UserController::class, 'index'])->name('users.index');
        Route::get('/e5w9n/creer',          [UserController::class, 'create'])->name('users.create');
        Route::post('/e5w9n',               [UserController::class, 'store'])->name('users.store');
        Route::get('/e5w9n/{user}/editer',  [UserController::class, 'edit'])->name('users.edit');
        Route::put('/e5w9n/{user}',         [UserController::class, 'update'])->name('users.update');
        Route::delete('/e5w9n/{user}',      [UserController::class, 'destroy'])->name('users.destroy');
    });
});
