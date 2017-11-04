<?php

namespace Keevitaja\Linguist;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str;

class Linguist
{
    protected $current;

    protected $config;

    protected $request;

    protected $app;

    public function __construct(UrlGenerator $url, Request $request, Application $app, Config $config)
    {
        $this->current = env('LOCALE');
        $this->config = $config->get('linguist');
        $this->url = $url;
        $this->request = $request;
        $this->app = $app;
    }

    public function current()
    {
        return $this->current;
    }

    public function enabled()
    {
        return $this->config['enabled'];
    }

    public function localize($locale = null)
    {
        $locale = is_null($locale) ? $this->current : $locale;

        $this->app->setLocale($locale);

        if ($this->shouldLocalize()) {
            $this->url->forceRootUrl($this->request->root().'/'.$locale);
        }
    }

    public function hasDefaultSlug()
    {
        if ( ! $this->isDefault()) {
            return false;
        }

        $pattern = '/^\/('.$this->current().')(\/|(?:$)|(?=\?))/';

        return (boolean) preg_match($pattern, $this->request->server('ORIGINAL_REQUEST_URI'));
    }

    public function isDefault($locale = null)
    {
        return (is_null($locale) ? $this->current : $locale) == $this->config['default'];
    }

    public function isDefaultHidden()
    {
        return $this->config['hide_default'];
    }

    public function isDefaultDenied()
    {
        return $this->config['deny_default'];
    }

    public function shouldLocalize($locale = null)
    {
        return ! $this->isDefault($locale) || ! $this->isDefaultHidden();
    }
}
