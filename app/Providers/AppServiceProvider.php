<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use App\Http\Responses\CustomLoginResponse;
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::unguard();
        Schema::defaultStringLength(191);
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
    //     Schema::defaultStringLength(191);
    // }
    /**
     * Bootstrap any application services.
     */
}
