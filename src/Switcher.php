<?php

namespace Keevitaja\Linguist;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Keevitaja\Linguist\Linguist;

class Switcher
{
    /**
     * @param Keevitaja\Linguist\Linguist $linguist
     * @param Illuminate\Contracts\Routing\UrlGenerator $url
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function handle(Linguist $linguist, UrlGenerator $url, Request $request)
    {
        $urls = [];
        $root = $request->root();
        $uri = str_replace($root, '', $url->full());

        foreach ($linguist->enabled() as $locale) {
            $rt = $root;

            if ($linguist->shouldLocalize($locale)) {
                $rt .= '/'.$locale;
            }

            $urls[$locale] = $rt.$uri;
        }

        return collect($urls);
    }
}
