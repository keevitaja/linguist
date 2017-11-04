<?php

namespace Keevitaja\Linguist;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str;

class Linguist
{
    protected $enabled = false;

    protected $current;

    protected $config;

    protected $request;

    public function __construct(UrlGenerator $url, Request $request)
    {
        $this->current = env('LOCALE');
        $this->config = config('localization');
        $this->url = $url;
        $this->request = $request;
    }

    public function current()
    {
        return $this->current;
    }

    public function enable($locale = null)
    {
        $this->enabled = true;
        $locale = is_null($locale) ? $this->current : $locale;

        app()->setLocale($locale);

        if ($this->shouldLocalize()) {
            $this->url->forceRootUrl($this->request->root().'/'.$locale);
        }
    }

    public function disable()
    {
        $this->enabled = false;
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

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function shouldLocalize($locale = null)
    {
        if ( ! $this->isEnabled()) {
            return false;
        }

        return ! $this->isDefault($locale) || ! $this->isDefaultHidden();
    }

    public function switcher()
    {
        $urls = [];
        $enabled = $this->enabled;
        $this->enabled = true;
        $uri = str_replace($this->request->root(), '', url()->full());

        foreach ($this->config['enabled'] as $locale) {
            $root = $this->request->root();

            if ($this->shouldLocalize($locale)) {
                $root .= '/'.$locale;
            }

            $urls[$locale] = $root.$uri;
        }

        $this->enabled = $enabled;

        return collect($urls);
    }
}
