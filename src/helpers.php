<?php

if ( ! function_exists('lnk_to')) {
    /**
     * Generate localized HTML anchor tag
     *
     * @param  string  $uri
     * @param  string  $title
     * @param  array   $attributes
     * @param  array   $extra
     * @param  mixed  $secure
     * @param  boolean $slug
     *
     * @return string
     */
    function lnk_to(
        $uri, 
        $title,
        $attributes = [],
        $extra = [],
        $secure = null,
        $slug = false
    ) {
        return app('Keevitaja\Linguist\HtmlBuilder')
            ->linkTo($uri, $title, $attributes, $extra, $secure, $slug);
    }
}

if ( ! function_exists('lnk_to_route')) {
    /**
     * Generate localized HTML anchor tag to named route
     *
     * @param  string  $name
     * @param  string  $title
     * @param  array   $parameters
     * @param  array   $attributes
     * @param  boolean $slug
     *
     * @return string
     */
    function lnk_to_route(
        $name,
        $title,
        $parameters = [],
        $attributes = [],
        $extra = [],
        $secure = null,
        $slug = false
    ) {
        return app('Keevitaja\Linguist\HtmlBuilder')
            ->linkToRoute($name, $title, $parameters, $attributes, $extra, $secure, $slug);
    }
}