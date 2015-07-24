<?php

namespace Keevitaja\Linguist;

use Keevitaja\Linguist\Linguist;

class HtmlBuilder
{
    /**
     * Linguist
     *
     * @var object
     */
    protected $linguist;

    public function __construct(Linguist $linguist)
    {
        $this->linguist = $linguist;
    }

    /**
     * Parse anchor attributes
     *
     * @param  array  $attributes
     *
     * @return string
     */
    protected function parseAttributes(array $attributes)
    {
        $attributePairs = [];

        foreach ($attributes as $attribute => $value) {
            $attributePairs[] = $attribute.'="'.$value.'"';
        }

        return implode(" ", $attributePairs);
    }

    /**
     * Generate HTML link
     *
     * @param  string $url
     * @param  string $title
     * @param  array  $attributes
     *
     * @return string
     */
    protected function link($url, $title, $attributes = [])
    {
        $attributes = $this->parseAttributes($attributes);
        $title = empty(trim($title)) ? $url : $title;

        return '<a href="'.$url.'" '.$attributes.'>'.$title.'</a>';
    }

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
    public function linkTo(
        $uri,
        $title,
        $attributes = [],
        $extra = [],
        $secure = null,
        $slug = false
    ) {
        $url = $this->linguist->url($uri, $extra, $secure, $slug);

        return $this->link($url, $title, $attributes);
    }

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
    public function linkToRoute(
        $name,
        $title,
        $parameters = [],
        $attributes = [],
        $extra = [],
        $secure = null,
        $slug = false
    ) {
        $url = $this->linguist->route($name, $parameters, $extra, $secure, $slug);

        return $this->link($url, $title, $attributes);
    }
}