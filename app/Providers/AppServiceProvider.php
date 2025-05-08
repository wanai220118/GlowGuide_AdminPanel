<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::unguard();
    }
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    // public function boot()
    // {
    //     Filament::serving(function () {
    //         \Filament\Facades\Filament::registerRenderHook(
    //             'head.start',
    //             fn () => '<link rel="icon" href="/images/Logo.png" type="image/png" />'
    //         );
    //     });
    // }
    /**
     * Bootstrap any application services.
     */
}
