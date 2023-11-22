<?php
/**
 * GammaMatrix
 *
 */

namespace Tests;

use GammaMatrix\Playground\Test\OrchestraTestCase;
// use Illuminate\Foundation\Testing\DatabaseTransactions;
use GammaMatrix\Playground\ServiceProvider as PlaygroundServiceProvider;
use GammaMatrix\Playground\Auth\ServiceProvider;
use Illuminate\Contracts\Config\Repository;

/**
 * \Tests\TestCase
 *
 */
class TestCase extends OrchestraTestCase
{
    // use DatabaseTransactions;

    protected function getPackageProviders($app)
    {
        return [
            PlaygroundServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    // /**
    //  * Define database migrations.
    //  *
    //  * @return void
    //  */
    // protected function defineDatabaseMigrations()
    // {
    //     $this->loadLaravelMigrations(['--database' => 'testbench']);
    //     $this->loadMigrationsFrom(workbench_path('database/migrations'));
    // }

    /**
     * Set up the environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', '\GammaMatrix\\Playground\\Test\\Models\\User');
        $app['config']->set('playground.auth.verify', 'user');

        $app['config']->set('playground-auth.token.sanctum', false);

        // $app['config']->set('playground.load.routes', true);
        // $app['config']->set('playground.routes.about', true);
        // $app['config']->set('playground.routes.dashboard', true);
        // $app['config']->set('playground.routes.home', true);
        // $app['config']->set('playground.routes.sitemap', true);
        // $app['config']->set('playground.routes.theme', true);
        // $app['config']->set('playground.routes.welcome', true);
    }
}
