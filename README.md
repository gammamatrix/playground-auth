# Playground Auth

The Playground authentication package.

NOTE: This authentication code originally came from Laravel.

## Installation

You can install the package via composer:

```bash
composer require gammamatrix/playground-auth
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="GammaMatrix\Playground\Auth\ServiceProvider" --tag="playground-config"
```

See the contents of the published config file: [config/playground-auth.php](config/playground-auth.php)

You can publish the routes file with:
```bash
php artisan vendor:publish --provider="GammaMatrix\Playground\Auth\ServiceProvider" --tag="playground-routes"
```

See the authentication routes: [routes/auth.php](routes/auth.php)

## Commands

This package adds a command to hash a password from the command line:

```bash
artisan auth:hash-password 'some password'
```

```bash
artisan auth:hash-password 'some password' --json --pretty
```
```json
{
    "password": "$2y$10$langzXKRw1GgO6VgF0IrSecqxi3gAsU5NgmmERT\/2pQXg06mSbEjS"
}
```

## Configuration

Disable options:

```
PLAYGROUND_AUTH_LOAD_COMMANDS=false
PLAYGROUND_AUTH_LOAD_ROUTES=false
PLAYGROUND_AUTH_LOAD_VIEWS=false
```

```
PLAYGROUND_AUTH_SITEMAP_ENABLE=false
PLAYGROUND_AUTH_SITEMAP_GUEST=false
PLAYGROUND_AUTH_SITEMAP_USER=false
PLAYGROUND_AUTH_SITEMAP_VIEW=false
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Jeremy Postlethwaite](https://github.com/gammamatrix)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
