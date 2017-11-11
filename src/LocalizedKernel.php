<?php

namespace Keevitaja\Linguist;

use App\Http\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Keevitaja\Linguist\UriFixer;

class LocalizedKernel extends Kernel
{

    /**
     * @param Illuminate\Contracts\Foundation\Application $app
     * @param Illuminate\Routing\Router $router
     */
    public function __construct(Application $app, Router $router)
    {
        (new UriFixer)->fixit();

        parent::__construct($app, $router);
    }
}
