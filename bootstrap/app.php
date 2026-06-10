<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        // Utilisateur déjà connecté sur une route guest → tableau de bord
        $middleware->redirectUsersTo(fn () => route('ads.index'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Utilisateur non connecté sur une route protégée → 404 (ne révèle pas l'existence de la route)
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            abort(404);
        });
    })->create();
