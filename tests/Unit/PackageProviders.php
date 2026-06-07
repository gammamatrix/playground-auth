<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Auth;

use Playground\ServiceProvider;

/**
 * \Tests\Unit\Playground\Auth\PackageProviders
 */
trait PackageProviders
{
    protected function getPackageProviders($app)
    {
        return [
            \Playground\Test\ServiceProvider::class,
            ServiceProvider::class,
            \Playground\Auth\ServiceProvider::class,
        ];
    }
}
