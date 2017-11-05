<?php

namespace Keevitaja\Linguist;

use App\Http\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

class LocalizedKernel extends Kernel
{

    /**
     * @param Illuminate\Contracts\Foundation\Application $app
     * @param Illuminate\Routing\Router $router
     */
    public function __construct(Application $app, Router $router)
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $this->localize($_SERVER['REQUEST_URI']);
        }

        parent::__construct($app, $router);
    }

    /**
     * @param string $uri
     *
     * @return void
     */
    protected function localize($uri)
    {
        $_SERVER['ORIGINAL_REQUEST_URI'] = $uri;
        $config = require_once __DIR__.'/../config/linguist.php';
        $pattern = '/^\/('.implode('|', $config['enabled']).')(\/|(?:$)|(?=\?))/';

        if (preg_match($pattern, $uri, $matches)) {
            $_SERVER['REQUEST_URI'] = preg_replace($pattern, '/', $uri);

            putenv('LOCALE='.$matches[1]);
        } else {
            putenv('LOCALE='.$config['default']);
        }
    }
}
