<?php

namespace Keevitaja\Linguist;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;

class Switcher
{
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
