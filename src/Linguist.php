<?php

namespace Keevitaja\Linguist;

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
        return defined('LOCALE') ? LOCALE : $this->defaultLocale();
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
        return config('linguist.hide_default') AND $this->defaultLocale() == $slug;
    }

    /**
     * Prepend i18n slug to URI
     *
     * @param  string  $uri
     * @param  boolean $slug
     *
     * @return string
     */
    public function uri($uri, $slug = false)
    {
        $slug = $this->validatedSlug($slug);

        if ($this->isHidden($slug)) {
            return $uri;
        }

        return $slug.'/'.ltrim($uri, '/');
    }

    /**
     * Generate localized URL from URI
     *
     * @param  string  $uri
     * @param  array   $extra
     * @param  mixed  $secure
     * @param  boolean $slug
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
     * @param  boolean $slug
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
        $slug = $this->validatedSlug($slug);

        if ($this->isHidden($slug)) {
            return $this->url->route($name, $parameters);
        }

        $uri = $slug.$this->url->route($name, $parameters, false);

        return $this->url->to($uri, $extra, $secure);
    }
}