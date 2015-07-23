<?php

namespace Keevitaja\Linguist;

use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Keevitaja\Linguist\InvalidLocaleException;

class Linguist
{
    /**
     * Http Request
     *
     * @var object
     */
    protected $request;

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

    public function __construct(Request $request, Repository $config, UrlGenerator $url)
    {
        $this->request = $request;
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
            throw new InvalidLocaleException('Locale slug [' . $slug . '] not configured!');
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
    protected function isHidden($slug)
    {
        $hide = $this->config->get('linguist.hide_default');

        return $hide AND $this->defaultLocale() == $slug;
    }

    /**
     * Prepend i18n slug to uri
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
     * Prepend i18n slug to uri and return url
     *
     * @param  string  $uri
     * @param  boolean $slug
     *
     * @return string
     */
    public function url($uri, $slug = false)
    {
        $uri = $this->uri($uri, $slug);
        $root = $this->request->root();

        return $root.'/'.$uri;
    }

    /**
     * Prepend i18n slug to route
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  boolean $slug
     *
     * @return string
     */
    public function route($name, $parameters = [], $slug = false)
    {
        $slug = $this->validatedSlug($slug);

        $url = $this->url->route($name, $parameters);

        if ($this->isHidden($slug)) {
            return $url;
        }

        $root = $this->request->root();

        $uri = ltrim(str_replace($root, '', $url), '/');

        return $root.'/'.$slug.'/'.$uri;
    }
}