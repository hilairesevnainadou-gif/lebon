<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Vue par défaut de pagination : l'app n'utilise pas Tailwind
        // (uniquement public/css/lebon.css), donc la vue Tailwind par
        // défaut de Laravel s'affichait sans aucun style.
        Paginator::defaultView('vendor.pagination.lebon');
        Paginator::defaultSimpleView('vendor.pagination.lebon');
    }
}
