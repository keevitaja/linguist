<?php

namespace Keevitaja\Linguist;

use Illuminate\Config\Repository;
use Illuminate\Routing\UrlGenerator;
use Keevitaja\Linguist\InvalidLocaleException;

class Linguist
{
    /**
     * Config Repository
     *
     * @var object
     */
    protected $config;

    /**
     * Url Generator
     *
     * @var object
     */
    protected $url;

    public function __construct(Repository $config, UrlGenerator $url)
    {
        $this->config = $config;
        $this->url = $url;
    }

    /**
     * Get default locale
     *
     * @return string
     */
    public function defaultLocale()
    {
        return $this->config->get('linguist.default');
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

        $locales = $this->config->get('linguist.locales');

        /*
         * Throw exception if i18n slug is not found in config locales array
         */
        if ( ! in_array($slug, $locales)) {
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
        $hide = $this->config->get('linguist.hide_default');

        return $hide AND $this->defaultLocale() == $slug;
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

        if ($this->isHidden($slug)) return $uri;

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