<?php

namespace Keevitaja\Linguist;

use Illuminate\Support\Facades\Facade;

class HtmlBuilderFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'linguisthtmlbuilder'; }
}