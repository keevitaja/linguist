<?php

/*
|--------------------------------------------------------------------------
| Remove locale slug from REQUEST_URI
|--------------------------------------------------------------------------
|
| This hack removes locale slug from the REQUEST_URI
|
*/

$config = include 'resources/config/linguist.php';

$pattern = '/^\/('.implode('|', $config['locales']).')\//';
$uri = $_SERVER['REQUEST_URI'];

if (preg_match($pattern, $uri, $matches)) {
    $uri = preg_replace($pattern, '/', $uri);

    $_SERVER['REQUEST_URI'] = $uri;

    define('LOCALE', $matches[1]);
}