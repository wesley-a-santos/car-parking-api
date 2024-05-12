<?php

namespace App\Providers;

use App\Models\Parking;
use App\Observers\ParkingObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Vehicle;
use App\Observers\VehicleObserver;

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
        Vehicle::observe(VehicleObserver::class);
        Parking::observe(ParkingObserver::class);
    }
}
