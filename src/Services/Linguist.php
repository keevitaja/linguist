<?php

namespace Keevitaja\Linguist\Services;

use Illuminate\Routing\UrlGenerator;
use Keevitaja\Linguist\InvalidLocaleException;

class Linguist
{
    /**
     * Url Generator
     *
     * @var object
     */
    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * Get default locale
     *
     * @return string
     */
    public function defaultLocale()
    {
        return config('linguist.default');
    }

    /**
     * Get working locale or default on fail
     *
     * @return string
     */
    public function workingLocale()
    {
        return config('app.locale');
    }

    /**
     * Get validated i18n slug
     *
     * @param  string $slug
     *
     * @return string
     */
    protected function validatedSlug($slug)
    {
        $slug = $slug ?: $this->workingLocale();

        /*
         * Throw exception if i18n slug is not found in config locales array
         */
        if ( ! in_array($slug, config('linguist.locales'))) {
            throw new InvalidLocaleException('Locale slug ['.$slug.'] not configured!');
        }

        return $slug;
    }

    /**
     * Is locale slug hidden
     *
     * @param  string  $slug
     *
     * @return boolean
     */
    public function isHidden($slug)
    {
        return config('linguist.hide_default') && $this->defaultLocale() == $slug;
    }

    /**
     * Generate localized URI
     *
     * @param  string  $uri
     * @param  mixed $slug
     *         
     * @return string
     */
    public function uri($uri, $slug = false)
    {
        $slug = $this->validatedSlug($slug);

        if ($this->isHidden($slug)) {
            return $uri;
        }

        return trim($slug.'/'.ltrim($uri, '/'), '/');
    }

    /**
     * Generate localized URL from URI
     *
     * @param  string  $uri
     * @param  array   $extra
     * @param  mixed   $secure
     * @param  mixed   $slug
     *
     * @return string
     */
    public function url($uri, $extra = [], $secure = null, $slug = false)
    {
        return $this->url->to($this->uri($uri, $slug), $extra, $secure);
    }

    /**
     * Generate localized URL from named route
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  array   $extra
     * @param  mixed   $secure
     * @param  mixed   $slug
     *
     * @return string
     */
    public function route(
        $name,
        $parameters = [],
        $extra = [],
        $secure = null, 
        $slug = false
    ) {
        $uri = $this->url->route($name, $parameters, false);

        return $this->url($uri, $extra, $secure, $slug);
    }
}