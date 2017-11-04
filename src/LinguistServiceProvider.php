<?php

namespace Keevitaja\Linguist;

use Illuminate\Support\ServiceProvider;

class LinguistServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/linguist.php' => config_path('linguist.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/linguist.php', 'linguist');
    }

    public function register()
    {
        $this->app->singleton(Linguist::class);
    }
}
