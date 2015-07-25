<?php

namespace Keevitaja\Linguist;

use Illuminate\Support\Facades\Facade;

class LinguistFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'linguist'; }
}