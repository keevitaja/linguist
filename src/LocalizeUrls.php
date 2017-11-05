<?php

namespace Keevitaja\Linguist;

use Closure;
use Keevitaja\Linguist\Linguist;

class LocalizeUrls
{
    /** @var Keevitaja\Linguist\Linguist */
    protected $linguist;

    public function __construct(Linguist $linguist)
    {
        $this->linguist = $linguist;
    }

    /**
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     *
     * @return Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        if ($this->linguist->hasDefaultSlug() && $this->linguist->isDefaultDenied()) {
            abort(404);
        }

        $this->linguist->localize();

        return $next($request);
    }
}
