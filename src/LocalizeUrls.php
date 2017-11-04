<?php

namespace Keevitaja\Linguist;

use App\Features\Linguist;
use Closure;

class LocalizeUrls
{
    protected $linguist;

    public function __construct(Linguist $linguist)
    {
        $this->linguist = $linguist;
    }

    public function handle($request, Closure $next)
    {
        if ($this->linguist->hasDefaultSlug() && $this->linguist->isDefaultDenied()) {
            abort(404);
        }

        $this->linguist->enable();

        return $next($request);
    }
}
