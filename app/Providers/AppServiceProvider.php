<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
            $this->app->useStoragePath('/tmp/storage');
            config(['view.compiled' => '/tmp/storage/framework/views']);
        }
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
