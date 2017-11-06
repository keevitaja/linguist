# Linguist - Localization for Laravel

This package provides an easy multilingual localization support for the Laravel framework.

## Installation

Linguist is very easy to use. The locale slug is removed from the REQUEST_URI leaving the developer with the cleanest multilingual environment possible.

Install using Composer:

```
composer require keevitaja/linguist
```

Use `LocalizedKernel` instead the one that ships with Laravel in `bootstrap/app.php`:

```php
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    Keevitaja\Linguist\LocalizedKernel::class
);
```

## Usage

Use `LocalizeUrls` middleware or `Linguist->localize()` in your ServiceProvider to get the localization support.

If you use the middleware make sure you set it in your web middleware stack as first item. Otherwise some redirections will not work.

`UrlGenerator` will add the locale slug in front of the URI when needed. No extra actions needed.

```
Route::get('people', ['as' => 'people.index', 'uses' => ''PeopleController@index'']);
```

```
{{ route('people.index') }} or {{ url('people') }}
```

You will get localized urls like `http://site.com/fr/about` and `http://site.com/about` where the last one is using the default locale.


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
