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

        $this->app->config->set(
            'app.locale', 
            defined('LOCALE') ? LOCALE : config('linguist.default')
        );

        $this->app->bind('linguist', function() {
            return $this->app->make('Keevitaja\Linguist\Linguist');
        });

        $this->app->bind('linguisthtmlbuilder', function() {
            return $this->app->make('Keevitaja\Linguist\HtmlBuilder');
        });
    }
}
