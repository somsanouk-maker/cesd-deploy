<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Models\Laboratory;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        // Store short aliases (not full class names) in bookings.bookable_type,
        // so the DB column stays stable across any future namespace refactor.
        Relation::morphMap([
            'equipment' => Equipment::class,
            'laboratory' => Laboratory::class,
        ]);
    }
}
