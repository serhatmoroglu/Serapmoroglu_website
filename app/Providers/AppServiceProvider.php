<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        // Eski MySQL sürümlerinde (anahtar sınırı 1000 bayt) tablo oluşturabilmek için
        Schema::defaultStringLength(191);
    }
}
