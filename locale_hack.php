<?php

/*
|--------------------------------------------------------------------------
| Remove locale slug from REQUEST_URI
|--------------------------------------------------------------------------
|
| This hack removes locale slug from the REQUEST_URI
|
*/

/*
 * Make sure you have locales described in config also
 */
$pattern = '/^\/(en|fr|et)\//';

$URI = $_SERVER['REQUEST_URI'];

if (preg_match($pattern, $URI, $matches)) {
    $URI = preg_replace($pattern, '/', $URI);

    $_SERVER['REQUEST_URI'] = $URI;

    define('LOCALE', $matches[1]);
}