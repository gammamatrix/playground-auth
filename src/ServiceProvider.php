<?php
/**
 * Playground
 */
namespace Playground\Auth;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

/**
 * \Playground\Auth\ServiceProvider
 */
class ServiceProvider extends AuthServiceProvider
{
    public const VERSION = '73.0.0';

    protected string $package = 'playground-auth';

    /**
     * Bootstrap any package services.
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @var array<string, mixed> $config
         */
        $config = config($this->package);

        if (! empty($config['load']) && is_array($config['load'])) {

            if (! empty($config['load']['translations'])) {
                $this->loadTranslationsFrom(
                    dirname(__DIR__).'/resources/lang',
                    $this->package
                );
            }

            if ($this->app->runningInConsole()) {
                if (! empty($config['load']['commands'])) {
                    $this->commands([
                        Console\Commands\HashPassword::class,
                    ]);
                }

                // Publish configuration
                $this->publishes([
                    sprintf('%1$s/config/%2$s.php', dirname(__DIR__), $this->package) => config_path(sprintf('%1$s.php', $this->package)),
                ], 'playground-config');
            }
        }

        $this->about();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            sprintf('%1$s/config/%2$s.php', dirname(__DIR__), $this->package),
            $this->package
        );
    }

    public function about(): void
    {
        $config = config($this->package);
        $config = is_array($config) ? $config : [];

        $load = ! empty($config['load']) && is_array($config['load']) ? $config['load'] : [];
        $token = ! empty($config['token']) && is_array($config['token']) ? $config['token'] : [];

        $version = $this->version();

        AboutCommand::add('Playground: Auth', fn () => [
            '<fg=yellow;options=bold>Load</> Commands' => ! empty($load['commands']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=yellow;options=bold>Load</> Translations' => ! empty($load['translations']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            '<fg=cyan;options=bold>Token</> [Expires]' => sprintf('[%s]', $token['expires']),
            '<fg=cyan;options=bold>Token Name</>' => $token['name'],
            '<fg=cyan;options=bold>Token Roles</>' => ! empty($token['roles']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=cyan;options=bold>Token Privileges</>' => ! empty($token['privileges']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=cyan;options=bold>Token Sanctum</>' => ! empty($token['sanctum']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            'Package' => $this->package,
            'Version' => $version,
        ]);
    }

    public function version(): string
    {
        return static::VERSION;
    }
}
