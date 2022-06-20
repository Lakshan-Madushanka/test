<?php

namespace App\Providers;

use App\ExternalServices\ApiServices\CalenderService\GoogleCalenderService;
use App\ExternalServices\ApiServices\Contracts\CalenderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->bind(CalenderService::class, GoogleCalenderService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('local')) {
            Model::preventLazyLoading();
        }
    }
}
