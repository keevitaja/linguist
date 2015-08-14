<?php

namespace Keevitaja\Linguist\Facades;

use Illuminate\Support\Facades\Facade;

class LinguistFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'linguist'; }
}