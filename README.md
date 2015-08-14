# Linguist - Localization for Laravel
[![Build Status](https://travis-ci.org/keevitaja/linguist.svg?branch=master)](https://travis-ci.org/keevitaja/linguist) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/keevitaja/linguist/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/keevitaja/linguist/?branch=master)

This package provides an easy localization support for the Laravel framework.

Read more about the approach and package from [keevitaja.com](http://keevitaja.com/2015/07/multilingual-laravel-applications-right-way) blog.

## Installation

Install using composer

```
"keevitaja/linguist": "1.0.*"
```

Add service provider to `config/app.php`

```php
Keevitaja\Linguist\LinguistServiceProvider::class
```

Add aliases to `config/app.php`

```php
'Linguist'  => Keevitaja\Linguist\LinguistFacade::class,
'LinguistHtml'  => Keevitaja\Linguist\HtmlBuilderFacade::class
```

Swap `HttpKernel` in `app/Http/Kernel.php`

```php
<?php

namespace App\Http;

//use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Keevitaja\Linguist\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
```

Publish configuration file to `config/`

```
php artisan vendor:publish --provider="Keevitaja\Linguist\LinguistServiceProvider" --tag="config"
```

Set your locales in `config/linguist.php`

```php
<?php

return [

    /*
     * i18n locale slugs
     */
    'locales' => ['en', 'fr', 'et'],

    /*
     * Hide i18n slug for default locale
     */
    'hide_default' => false,

    /*
     * Default i18n locale slug
     */
    'default' => 'en'
];
```

## Usage

Linguist is very easy to use. The locale slug is removed from the REQUEST_URI leaving the developer with the cleanest multilingual environment possible.

Linguist uses Laravel [UrlGenerator](http://laravel.com/api/5.1/Illuminate/Routing/UrlGenerator.html#method_to) for the URL generation.

### Routing

Linguist hides locale slug from the framework. Because of that routing is done as it would be normally:

```php
Route::get('about', 'AboutController@index');
```

The route above will catch both `http://site.com/about`, `http://site.com/en/about` and `http://site.com/et/about`. URL without a locale slug is treated as a default locale.

Sometimes there is a need for translated URLs: for the English content `http://site.com/en/people` and for the French content `http://site.com/fr/personnes` . 

With Linguist no extra configuration is need. Just create two routes with identical destinations:

```php
Route::get('people', 'PeopleController@index');
Route::get('personnes', 'PeopleController@index');
```

### Keevitaja\Linguist\Linguist

Provides locale information and generates localized URLs.

#### Get default locale

```php
/**
 * Get default locale
 *
 * @return string
 */
public function defaultLocale()
```

#### Get working locale or default on fail

```php
/**
 * Get working locale or default on fail
 *
 * @return string
 */
public function workingLocale()
```

#### Is locale slug hidden

```php
/**
 * Is locale slug hidden
 *
 * @param  string  $slug
 *
 * @return boolean
 */
public function isHidden($slug)
```

#### Generate localized URI

```php
/**
 * Generate localized URI
 *
 * @param  string  $uri
 * @param  mixed $slug
 *
 * @return string
 */
public function uri($uri, $slug = false)
```

#### Generate localized URL from URI

```php
/**
 * Generate localized URL from URI
 *
 * @param  string  $uri
 * @param  array   $extra
 * @param  mixed   $secure
 * @param  mixed   $slug
 *
 * @return string
 */
public function url($uri, $extra = [], $secure = null, $slug = false)
```

#### Generate localized URL from named route

```php
/**
 * Generate localized URL from named route
 *
 * @param  string  $name
 * @param  array   $parameters
 * @param  array   $extra
 * @param  mixed   $secure
 * @param  mixed   $slug
 *
 * @return string
 */
public function route(
    $name,
    $parameters = [],
    $extra = [],
    $secure = null, 
    $slug = false
)
```

### Keevitaja\Linguist\HtmlBuilder

Generates localized HTML anchor tags

#### Generate localized HTML anchor tag

```php
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
)
```

#### Generate localized HTML anchor tag to named route

```php
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
)
```

#### Parse HTML anchor tag

```php
/**
 * Parse HTML anchor tag
 *
 * @param  string $url
 * @param  string $title
 * @param  array  $attributes
 *
 * @return string
 */
public function link($url, $title, $attributes = [])
```

### Helpers

There are available helpers for HTML tag generation.

#### lnk_to()

```php
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
)
```

#### lnk_to_route()

```php
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
)
```

### Facades

Linguist also comes with Facades

```

Linguist => Keevitaja\Linguist\Linguist
LinguistHtml => Keevitaja\Linguist\HtmlBuilder
```

## Examples

The examples below are generated by artisan tinker and the default Linguist configuration is in use.

```
>>> Linguist::workingLocale();
=> "en"

>>> Linguist::url('some/path');
=> "http://localhost/en/some/path"

>>> Linguist::url('some/path', [], true, 'fr');
=> "https://localhost/fr/some/path"

>>> Linguist::route('test.show', [3]);
=> "http://localhost/en/test/3"

>>> LinguistHtml::linkToRoute('test.show', 'Show third test', [3]);
=> "<a href="http://localhost/en/test/3">Show third test</a>"

>>> lnk_to('/', 'Home');
=> "<a href="http://localhost/en">Home</a>"

>>> Config::set('linguist.hide_default', true);
>>> lnk_to('/', 'Home');
=> "<a href="http://localhost">Home</a>"

>>> lnk_to_route('test.show', 'Show third test', [3], ['class' => 'button', 'data-delete']);
=> "<a class="button" data-delete href="http://localhost/en/test/3">Show third test</a>"

```

## The pledge

If this repository gets more than 50 stars, i will pledge myself to maintain it as long as the people use it!









 
