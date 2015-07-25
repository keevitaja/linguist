<?php

/**
 * This hack removes the i18n slug from the REQUEST_URI
 *
 * Totally harmless :)
 */
$config = include 'resources/config/linguist.php';

$pattern = '/^\/('.implode('|', $config['locales']).')\//';
$uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

if (preg_match($pattern, $uri, $matches)) {
    $uri = preg_replace($pattern, '/', $uri);

    $_SERVER['REQUEST_URI'] = $uri;

    define('LOCALE', $matches[1]);
}
