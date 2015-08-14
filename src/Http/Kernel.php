<?php

namespace Keevitaja\Linguist\Http;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Huge thanks to @RyanThePyro for the Kernel
 *
 * @link https://github.com/anomalylabs/streams-platform
 */
class Kernel extends HttpKernel
{
    public function __construct(Application $app, Router $router)
    {
        $this->defineLocale();
        
        parent::__construct($app, $router);
    }

    /**
     * Define locale based on current URI
     *
     * @return void
     */
    public function defineLocale()
    {
        $config = include __DIR__.'/../../config/linguist.php';

        $pattern = '/^\/('.implode('|', $config['locales']).')(?:\/|$)/';

        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

        if (preg_match($pattern, $uri, $matches)) {
            $_SERVER['REQUEST_URI'] = preg_replace($pattern, '/', $uri);

            define('LOCALE', $matches[1]);
        }
    }
}