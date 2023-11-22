<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Unit\GammaMatrix\Playground\Auth;

use GammaMatrix\Playground\Test\OrchestraTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use GammaMatrix\Playground\ServiceProvider as PlaygroundServiceProvider;
use GammaMatrix\Playground\Auth\ServiceProvider;
use Illuminate\Contracts\Config\Repository;

/**
 * \Tests\Unit\GammaMatrix\Playground\Auth\TestCase
 *
 */
class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions;

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
        // dd(__METHOD__);
        $app['config']->set('auth.providers.users.model', 'GammaMatrix\\Playground\\Test\\Models\\User');
        $app['config']->set('playground.auth.verify', 'user');

        $app['config']->set('playground-auth.redirect', true);
        $app['config']->set('playground-auth.session', true);

        $app['config']->set('playground-auth.token.roles', false);
        $app['config']->set('playground-auth.token.privileges', false);
        $app['config']->set('playground-auth.token.name', 'app-testing');
        $app['config']->set('playground-auth.token.sanctum', false);

        $app['config']->set('playground-auth.load.commands', true);
        $app['config']->set('playground-auth.load.routes', true);
        $app['config']->set('playground-auth.load.views', true);

        $app['config']->set('playground-auth.routes.confirm', true);
        $app['config']->set('playground-auth.routes.forgot', true);
        $app['config']->set('playground-auth.routes.logout', true);
        $app['config']->set('playground-auth.routes.login', true);
        $app['config']->set('playground-auth.routes.register', true);
        $app['config']->set('playground-auth.routes.reset', true);
        $app['config']->set('playground-auth.routes.token', true);
        $app['config']->set('playground-auth.routes.verify', true);

        $app['config']->set('playground-auth.sitemap.enable', true);
        $app['config']->set('playground-auth.sitemap.guest', true);
        $app['config']->set('playground-auth.sitemap.user', true);

        $app['config']->set('playground-auth.admins', []);
        $app['config']->set('playground-auth.managers', []);

    }
}
