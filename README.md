# Playground Auth

[![Playground CI Workflow](https://github.com/gammamatrix/playground-auth/actions/workflows/ci.yml/badge.svg?branch=develop)](https://raw.githubusercontent.com/gammamatrix/playground-auth/testing/develop/testdox.txt)
[![Test Coverage](https://raw.githubusercontent.com/gammamatrix/playground-auth/testing/develop/coverage.svg)](tests)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen)](.github/workflows/ci.yml#L120)

The Playground authentication and authorization package for [Laravel](https://laravel.com/docs/10.x) applications.

More information is available [on the Playground Auth wiki.](https://github.com/gammamatrix/playground-auth/wiki)

## Installation

You can install the package via composer:

```bash
composer require gammamatrix/playground-auth
```

**NOTE:** This package is required by [Playground: Login Blade](https://github.com/gammamatrix/playground-login-blade)

## `artisan about`

Playground Auth provides information in the `artisan about` command.

<img src="resources/docs/artisan-about-playground-auth.png" alt="screenshot of artisan about command with Playground Auth.">


## Configuration

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Playground\Auth\ServiceProvider" --tag="playground-config"
```

See the contents of the published config file: [config/playground-auth.php](config/playground-auth.php)

The default configuration utitlizes:
- Sanctum with role based abilities
- Users may have additional abilities in the [`Playground\Models\User`](https://github.com/gammamatrix/playground/blob/develop/src/Models/User.php): `users.abilities`
- The Playground user model uses a UUID primary key along with additional fields. See the [migration for `Playground\Models\User`](https://github.com/gammamatrix/playground/blob/develop/database/migrations-playground/2014_10_12_000000_create_users_table.php)

## Abilities, Privileges, Roles and Sanctum

Depending on your needs, there are multiple middleware, authentication and authorization options available.

Abilities may be used with wildcards at multiple levels. Optionally, these abilities may be used with [Sanctum](https://laravel.com/docs/10.x/sanctum) for API Tokens.

Here is an example of the configurable abilities:
```php
    'abilities' => [
        'root' => [
            '*',
        ],
        'admin' => [
            'app:*',
            'playground:*',
            'playground-auth:*',
            'playground-cms:*',
            'playground-cms-resource:*',
            'playground-matrix:*',
            'playground-matrix-resource:*',
        ],
        'manager' => [
            'app:view',

            'playground:view',

            'playground-auth:logout',
            'playground-auth:reset-password',
// ...
        'user' => [
            'app:view',
// ...
        ],
        // No abilities for guests:
        'guest' => [
            'none',
        ],
        // Allow abilities for guests:
        'guest' => [
             'app:view',

             'playground:view',

             'playground-auth:logout',
             'playground-auth:reset-password',
// ...
```

### Environment Variables

### User model types

Playground tests many different User model types to support any ecosystem.

Make sure your app is configured for the proper user model in the Laravel configuration:

```php
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
```

During testing, Playground tests user various models.
```php
config(['auth.providers.users.model' => 'Playground\\Models\\User'])
```


#### Loading

| env()                               | config()                            |
|-------------------------------------|-------------------------------------|
| `PLAYGROUND_AUTH_LOAD_COMMANDS`     | `playground-auth.load.commands`     |
| `PLAYGROUND_AUTH_LOAD_TRANSLATIONS` | `playground-auth.load.translations` |


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
    "hashed": "$2y$10$langzXKRw1GgO6VgF0IrSecqxi3gAsU5NgmmERT\/2pQXg06mSbEjS"
}
```

## PHPStan

Tests at level 9 on:
- `config/`
- `database/`
- `lang/`
- `resources/views/`
- `src/`
- `tests/Feature/`
- `tests/Unit/`

```sh
composer analyse
```

## Coding Standards

```sh
composer format
```

## Testing

```sh
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Jeremy Postlethwaite](https://github.com/gammamatrix)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
