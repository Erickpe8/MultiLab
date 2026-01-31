<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire; // Add this import

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            LoginResponseContract::class,
            LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            'primary' => Color::Indigo,
        ]);
        Livewire::component(\App\Livewire\Filament\CustomDatabaseNotifications::class);
    }
}
