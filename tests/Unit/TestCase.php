<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Auth;

use Playground\Auth\ServiceProvider;
// use Playground\ServiceProvider as PlaygroundServiceProvider;
use Playground\Test\OrchestraTestCase;

/**
 * \Tests\Unit\Playground\Auth\TestCase
 */
class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            // PlaygroundServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // dd(__METHOD__);
        $app['config']->set('auth.providers.users.model', 'Playground\\Test\\Models\\User');
        $app['config']->set('playground-auth.verify', 'user');

        // $app['config']->set('playground-auth.redirect', true);
        // $app['config']->set('playground-auth.session', true);

        // $app['config']->set('playground-auth.token.roles', false);
        // $app['config']->set('playground-auth.token.privileges', false);
        // $app['config']->set('playground-auth.token.name', 'app-testing');
        // $app['config']->set('playground-auth.token.sanctum', false);

        // $app['config']->set('playground-auth.admins', []);
        // $app['config']->set('playground-auth.managers', []);
    }
}
