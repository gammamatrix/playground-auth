<?php

declare(strict_types=1);
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
                    dirname(__DIR__).'/lang',
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

        $this->app->scoped('playground-auth-can', function () {
            return new Can();
        });
    }

    public function about(): void
    {
        $config = config($this->package);
        $config = is_array($config) ? $config : [];

        $load = ! empty($config['load']) && is_array($config['load']) ? $config['load'] : [];
        $token = ! empty($config['token']) && is_array($config['token']) ? $config['token'] : [];

        $listed_admins = 0;
        if (! empty($config['admins']) && is_array($config['admins'])) {
            $listed_admins = count($config['admins']);
        }

        $listed_managers = 0;
        if (! empty($config['managers']) && is_array($config['managers'])) {
            $listed_managers = count($config['managers']);
        }

        $abilties_root = 0;
        $abilties_root_one = '';
        $abilties_admin = 0;
        $abilties_admin_one = '';
        $abilties_manager = 0;
        $abilties_manager_one = '';
        $abilties_user = 0;
        $abilties_user_one = '';
        $abilties_guest = 0;
        $abilties_guest_one = '';

        if (! empty($config['abilities']) && is_array($config['abilities'])) {
            // Check abilities: root
            if (! empty($config['abilities']['root']) && is_array($config['abilities']['root'])) {
                $abilties_root = count($config['abilities']['root']);
                if ($abilties_root === 1) {
                    $abilties_root_one = json_encode($config['abilities']['root']);
                }
            }
            // Check abilities: admin
            if (! empty($config['abilities']['admin']) && is_array($config['abilities']['admin'])) {
                $abilties_admin = count($config['abilities']['admin']);
                if ($abilties_admin === 1) {
                    $abilties_admin_one = json_encode($config['abilities']['admin']);
                }
            }
            // Check abilities: manager
            if (! empty($config['abilities']['manager']) && is_array($config['abilities']['manager'])) {
                $abilties_manager = count($config['abilities']['manager']);
                if ($abilties_manager === 1) {
                    $abilties_manager_one = json_encode($config['abilities']['manager']);
                }
            }
            // Check abilities: user
            if (! empty($config['abilities']['user']) && is_array($config['abilities']['user'])) {
                $abilties_user = count($config['abilities']['user']);
                if ($abilties_user === 1) {
                    $abilties_user_one = json_encode($config['abilities']['user']);
                }
            }
            // Check abilities: guest
            if (! empty($config['abilities']['guest']) && is_array($config['abilities']['guest'])) {
                $abilties_guest = count($config['abilities']['guest']);
                if ($abilties_guest === 1) {
                    $abilties_guest_one = json_encode($config['abilities']['guest']);
                }
            }
        }

        $version = $this->version();

        AboutCommand::add('Playground: Auth', fn () => [
            '<fg=yellow;options=bold>Load</> Commands' => ! empty($load['commands']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=yellow;options=bold>Load</> Translations' => ! empty($load['translations']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            '<fg=yellow;options=bold>Debug Mode</>' => ! empty($config['debug']) && ! empty(config('app.debug')) ? '<fg=yellow;options=bold>ENABLED</>' : '<fg=green;options=bold>OFF</>',

            '<fg=cyan;options=bold>Verify</>' => ! empty($config['verify']) && is_string($config['verify']) ? sprintf('[%s]', $config['verify']) : '',

            '<fg=cyan;options=bold>Token</> [Abilities]' => sprintf('[%s]', $token['abilities']),
            '<fg=cyan;options=bold>Token</> [Expires]' => sprintf('[%s]', $token['expires']),
            '<fg=cyan;options=bold>Token</> [Name]' => sprintf('[%s]', $token['name']),
            '<fg=cyan;options=bold>Token Listed Admins</>' => ! empty($token['listed']) ? sprintf('<fg=green;options=bold>%1$d</>', $listed_admins) : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=cyan;options=bold>Token Listed Managers</>' => ! empty($token['listed']) ? sprintf('<fg=green;options=bold>%1$d</>', $listed_managers) : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=cyan;options=bold>Token Roles</>' => ! empty($token['roles']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=cyan;options=bold>Token Privileges</>' => ! empty($token['privileges']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=cyan;options=bold>Token Sanctum</>' => ! empty($token['sanctum']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            '<fg=cyan;options=bold>Abilities</> [admin]' => ! empty($abilties_admin) && empty($abilties_admin_one) ? sprintf('<fg=green;options=bold>%1$d</>', $abilties_admin) : sprintf('<fg=yellow;options=bold>%1$s</>', $abilties_admin_one),
            '<fg=cyan;options=bold>Abilities</> [guest]' => ! empty($abilties_guest) && empty($abilties_guest_one) ? sprintf('<fg=green;options=bold>%1$d</>', $abilties_guest) : sprintf('<fg=yellow;options=bold>%1$s</>', $abilties_guest_one),
            '<fg=cyan;options=bold>Abilities</> [manager]' => ! empty($abilties_manager) && empty($abilties_manager_one) ? sprintf('<fg=green;options=bold>%1$d</>', $abilties_manager) : sprintf('<fg=yellow;options=bold>%1$s</>', $abilties_manager_one),
            '<fg=cyan;options=bold>Abilities</> [root]' => ! empty($abilties_root) && empty($abilties_root_one) ? sprintf('<fg=green;options=bold>%1$d</>', $abilties_root) : sprintf('<fg=yellow;options=bold>%1$s</>', $abilties_root_one),
            '<fg=cyan;options=bold>Abilities</> [user]' => ! empty($abilties_user) && empty($abilties_user_one) ? sprintf('<fg=green;options=bold>%1$d</>', $abilties_user) : sprintf('<fg=yellow;options=bold>%1$s</>', $abilties_user_one),

            'Package' => $this->package,
            'Version' => $version,
        ]);
    }

    public function version(): string
    {
        return static::VERSION;
    }
}
