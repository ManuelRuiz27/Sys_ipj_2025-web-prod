<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Salud360Assignment;
use App\Models\Salud360Session;
use App\Policies\Salud360AssignmentPolicy;
use App\Policies\Salud360SessionPolicy;

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
        // Policies Salud360
        Gate::policy(Salud360Assignment::class, Salud360AssignmentPolicy::class);
        Gate::policy(Salud360Session::class, Salud360SessionPolicy::class);
    }
}
