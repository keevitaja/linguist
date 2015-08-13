<?php

/**
 * This hack removes the locale slug from the REQUEST_URI
 *
 * Totally harmless :)
 */

$config = include 'resources/config/linguist.php';

$pattern = '/^\/('.implode('|', $config['locales']).')(?:\/|$)/';

$uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

if (preg_match($pattern, $uri, $matches)) {
    $_SERVER['REQUEST_URI'] = preg_replace($pattern, '/', $uri);

    define('LOCALE', $matches[1]);
}
