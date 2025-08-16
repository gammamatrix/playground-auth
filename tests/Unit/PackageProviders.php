<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Auth;

/**
 * \Tests\Unit\Playground\Auth\PackageProviders
 */
trait PackageProviders
{
    protected function getPackageProviders($app)
    {
        return [
            \Playground\Test\ServiceProvider::class,
            \Playground\ServiceProvider::class,
            \Playground\Auth\ServiceProvider::class,
        ];
    }
}
