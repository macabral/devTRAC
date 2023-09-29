<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\library\LogService;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Library\LogService', function ($app) {
            return new LogService();
          });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
