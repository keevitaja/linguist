<?php

namespace Keevitaja\Linguist;

class UriFixer
{
    /**
     * @var array
     */
    protected $config;

    public function __construct()
    {
        $this->config = $this->getConfig();
    }

    /**
     * @return boolean
     */
    public function fixit()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return $this->removeLocaleSlug($_SERVER['REQUEST_URI']);
        }
    }

    /**
     * @param string $uri
     *
     * @return boolean
     */
    public function removeLocaleSlug($uri)
    {
        $_SERVER['ORIGINAL_REQUEST_URI'] = $uri;
        $pattern = '/^\/('.implode('|', $this->config['enabled']).')(\/|(?:$)|(?=\?))/';

        if (preg_match($pattern, $uri, $matches)) {
            $_SERVER['REQUEST_URI'] = preg_replace($pattern, '/', $uri);

            return define('INTERCEPTED_LOCALE', $matches[1]);
        }

        return define('INTERCEPTED_LOCALE', $this->config['default']);
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        $config = include __DIR__.'/../config/linguist.php';
        $publishedConfig = __DIR__.'/../../../../config/linguist.php';

        if (file_exists($publishedConfig)) {
            $published = include $publishedConfig;

            return array_merge($config, $published);
        }

        return $config;
    }
}
