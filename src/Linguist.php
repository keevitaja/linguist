<?php

namespace Keevitaja\Linguist;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Linguist
{
    /** @var array */
    protected $config;

    /** @var Illuminate\Contracts\Routing\UrlGenerator */
    protected $url;

    /** @var Illuminate\Http\Request */
    protected $request;

    /** @var Illuminate\Contracts\Foundation\Application */
    protected $app;

    public function __construct(UrlGenerator $url, Request $request, Application $app, Config $config)
    {
        $this->config = $config->get('linguist');
        $this->url = $url;
        $this->request = $request;
        $this->app = $app;
    }

    /**
     * @return array
     */
    public function enabled()
    {
        return $this->config['enabled'];
    }

    /**
     * @param string|null $locale
     *
     * @return void
     */
    public function localize($locale = null)
    {
        $locale = is_null($locale) ? INTERCEPTED_LOCALE : $locale;

        $this->app->setLocale($locale);

        if ($this->shouldLocalize()) {
            $this->url->forceRootUrl($this->request->root().'/'.$locale);
        }
    }

    /**
     * @return boolean
     */
    public function hasDefaultSlug()
    {
        if ( ! $this->isDefault()) {
            return false;
        }

        $pattern = '/^\/('.$this->app->getLocale().')(\/|(?:$)|(?=\?))/';

        return (boolean) preg_match($pattern, $this->request->server('ORIGINAL_REQUEST_URI'));
    }

    /**
     * @param string|null $locale
     *
     * @return boolean
     */
    public function isDefault($locale = null)
    {
        return (is_null($locale) ? $this->app->getLocale() : $locale) == $this->config['default'];
    }

    /**
     * @return boolean
     */
    public function isDefaultHidden()
    {
        return $this->config['hide_default'];
    }

    /**
     * @return boolean
     */
    public function isDefaultDenied()
    {
        return $this->config['deny_default'];
    }

    /**
     * @param string|null $locale
     *
     * @return boolean
     */
    public function shouldLocalize($locale = null)
    {
        return ! $this->isDefault($locale) || ! $this->isDefaultHidden();
    }
}
