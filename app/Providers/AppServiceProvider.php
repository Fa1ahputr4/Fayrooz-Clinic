<?php

namespace App\Providers;
use Livewire\Livewire;


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
        Livewire::component('user-management', \App\Livewire\user\UserManagement::class);
        Livewire::component('layanan-menu', \App\Livewire\layanan\LayananMenu::class);
    }
}
