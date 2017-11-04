# Linguist - Localization for Laravel

ALPHA

## Installation

Install using Composer:

```
composer require keevitaja/linguist dev-develop@dev
```

Use `LocalizedKernel` in `bootstrap/app.php`:

```php
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    Keevitaja\Linguist\LocalizedKernel::class
);
```


## Usage

Use `LocalizeUrls` middleware to get the localization support.