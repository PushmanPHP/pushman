<?php

namespace Pushman\Providers;

use Illuminate\Support\ServiceProvider;
use Pushman\Services\PushPrep;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pushprep', 'Pushman\Services\PushPrep');
    }
}
