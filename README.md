# Linguist - Multilingual urls and redirects for Laravel

This package provides an easy multilingual urls and redirection support for the Laravel framework. 

In short Laravel will generate localized urls for links and redirections.

```php
route('about') 
```

```
http://site.com/people
http://site.com/fr/people
```

## Installation

Linguist is very easy to use. The locale slug is removed from the REQUEST_URI leaving the developer with the cleanest multilingual environment possible.

Install using Composer:

```
composer require keevitaja/linguist
```

There are several options to make Linguist work.

### Option 1: Modify the `public/index.php`

Add following line after the vendor autoloading to your projects `public/index.php` file.

```php
(new Keevitaja\Linguist\UriFixer)->fixit();
```

End result would be this:

```php
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

(new Keevitaja\Linguist\UriFixer)->fixit();
```

### Option 2: Use LocalizedKernel

> Note: This option works only if you have not changed your applications root namespace. Default is `App`.

In your projects `bootstrap/app.php` swap the `App\Http\Kernel` with `Keevitaja\Linguist\LocalazedKernel`:

```php
/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    //App\Http\Kernel::class
    Keevitaja\Linguist\LocalizedKernel::class
);
```

### Option 3: modify the `App\Http\Kernel`

> Note: This also works with custom root namespace. 

```php
<?php

namespace App\Http;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Router;
use Keevitaja\Linguist\UriFixer;

class Kernel extends HttpKernel
{

    public function __construct(Application $app, Router $router)
    {
        (new UriFixer)->fixit();

        parent::__construct($app, $router);
    }
```

### Publish config

Finally you need to publish the Linguist config to set your enabled locales and default. 

```
php artisan vendor:publish --provider="Keevitaja\Linguist\LinguistServiceProvider"
```

## Usage

You can add the LocalizeUrls middleware your web middleware group as the first item to get the linguist support:

```php
/**
 * The application's route middleware groups.
 *
 * @var array
 */
protected $middlewareGroups = [
    'web' => [
        \Keevitaja\Linguist\LocalizeUrls::class,
```

> Note: This middleware has to be the first item in group!

Another option is to use Linguist in your applications service provider:

```php
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Keevitaja\Linguist\Linguist $linguist)
    {
        $linguist->localize();
    }
```

`UrlGenerator` will add the locale slug in front of the URI when needed. No extra actions needed.

```
Route::get('people', ['as' => 'people.index', 'uses' => ''PeopleController@index'']);
```

```
{{ route('people.index') }} or {{ url('people') }}
```

Switcher is a little helper to get the current URLs for the locale switcher.

```
$urls = dispatch_now(new \Keevitaja\Linguist\Switcher);
```

NB! Both config and route caching are working!

## Queues

To make localization work in queues you need to run `Linguist->localize($theLocaleYouWant)` inside the queued item.

## Configuration

You can publish the `linguist.php` configuration file where you can define which locales are enabled and the behaviour of the default locale. 

## Licence

MIT