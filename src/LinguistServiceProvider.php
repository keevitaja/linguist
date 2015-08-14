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
            __DIR__.'/../config/linguist.php' => config_path('linguist.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/linguist.php', 'linguist');

        $locale = defined('LOCALE') ? LOCALE : $this->app->config->get('linguist.default');

        $this->app->config->set('app.locale', $locale);

        $this->app->bind('linguist', function() {
            return $this->app->make('Keevitaja\Linguist\Services\Linguist');
        });

        $this->app->bind('linguisthtmlbuilder', function() {
            return $this->app->make('Keevitaja\Linguist\Services\HtmlBuilder');
        });
    }
}
