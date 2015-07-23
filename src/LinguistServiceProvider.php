<?php

namespace Keevitaja\Linguist;

use Illuminate\Support\ServiceProvider;

class LinguistServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/config/linguist.php' => config_path('linguist.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../resources/config/linguist.php', 'linguist');
    }
}
