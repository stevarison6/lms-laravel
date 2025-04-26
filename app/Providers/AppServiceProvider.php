<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
       Gate::define('create-resource', function ($user) {
       
        return $user->role === 'admin' || $user->role === 'instructor' ; // L'utilisateur doit être admin pour créer n'importe quelle ressource
});
    }
}
