<?php

if (! function_exists('linguist_asset')) {
    /**
     * Generate a linguist asset path for the application.
     *
     * @param $path
     * @param null $secure
     * @return string
     */
    function linguist_asset($path, $secure = null)
    {
        if (url()->isValidUrl($path)) {
            return $path;
        }

        $root = url()->formatRoot(url()->formatScheme($secure));
        $urlSegments = explode('/', $root);

        if (in_array(end($urlSegments), config('linguist.enabled'))) {
            $root = str_replace('/' . end($urlSegments), '', $root);
        }

        $index = 'index.php';

        return (
            str_contains($root, $index) ?
                str_replace('/' . $index, '', $root) : $root
        ) . '/' . trim($path, '/');
    }
}

if (! function_exists('secure_linguist_asset')) {
    /**
     * Generate a secure linguist asset path for the application.
     *
     * @param $path
     * @return string
     */
    function secure_linguist_asset($path)
    {
        return linguist_asset($path, true);
    }
}
