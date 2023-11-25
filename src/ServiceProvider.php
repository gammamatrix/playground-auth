<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Auth;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

/**
 * \GammaMatrix\Playground\Auth\ServiceProvider
 *
 */
class ServiceProvider extends AuthServiceProvider
{
    public const VERSION = '73.0.0';

    protected $package = 'playground-auth';

    /**
     * Bootstrap any package services.
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $config = config($this->package);

        if (!empty($config)) {
            $this->loadTranslationsFrom(
                dirname(__DIR__).'/resources/lang',
                'playground-auth'
            );

            if (!empty($config['load']['routes'])) {
                $this->loadRoutesFrom(dirname(__DIR__) . '/routes/auth.php');
            }

            if (!empty($config['load']['views'])) {
                $this->loadViewsFrom(
                    dirname(__DIR__).'/resources/views',
                    'playground-auth'
                );
            }

            if ($this->app->runningInConsole()) {
                if (!empty($config['load']['commands'])) {
                    $this->commands([
                        Console\Commands\HashPassword::class,
                    ]);
                }

                // Publish configuration
                $this->publishes([
                    dirname(__DIR__).'/config/playground-auth.php'
                        => config_path('playground-auth.php')
                ], 'playground-config');

                // Publish routes
                $this->publishes([
                    dirname(__DIR__).'/routes/auth.php'
                        => base_path('routes/playground-auth.php')
                ], 'playground-routes');
            }
        }

        $this->about();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/playground-auth.php',
            'playground-auth'
        );
    }

    public function about()
    {
        $config = config($this->package);

        $version = $this->version();

        $redirect = defined('\App\Providers\RouteServiceProvider::HOME') ? \App\Providers\RouteServiceProvider::HOME : null;

        AboutCommand::add('Playground Auth', fn () => [
            '<fg=yellow;options=bold>Load</> Commands' => !empty($config['load']['commands']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=yellow;options=bold>Load</> Routes' => !empty($config['load']['routes']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=yellow;options=bold>Load</> Views' => !empty($config['load']['views']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            '<fg=blue;options=bold>Redirect</>' => empty($config['redirect']) ? $redirect : $config['redirect'],
            '<fg=blue;options=bold>View</> [layout]' => sprintf('[%s]', $config['layout']),
            '<fg=blue;options=bold>View</> [prefix]' => sprintf('[%s]', $config['view']),

            '<fg=magenta;options=bold>Sitemap</> Views' => !empty($config['sitemap']['enable']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> Guest' => !empty($config['sitemap']['guest']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> User' => !empty($config['sitemap']['user']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> [view]' => sprintf('[%s]', $config['sitemap']['view']),

            '<fg=cyan;options=bold>Token</> [Expires]' => sprintf('[%s]', $config['token']['expires']),
            '<fg=cyan;options=bold>Token Name</>' => $config['token']['name'],
            '<fg=cyan;options=bold>Token Roles</>' => !empty($config['token']['roles']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=cyan;options=bold>Token Privileges</>' => !empty($config['token']['privileges']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=cyan;options=bold>Token Sanctum</>' => !empty($config['token']['sanctum']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            'Package' => $this->package,
            'Version' => $version,
        ]);
    }

    public function version()
    {
        return static::VERSION;
    }
}
