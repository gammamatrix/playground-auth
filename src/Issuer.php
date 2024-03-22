<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Playground\Models\Contracts\Abilities;
use Playground\Models\Contracts\Admin;
use Playground\Models\Contracts\Privileges;
use Playground\Models\Contracts\Role;

/**
 * \Playground\Auth\Issuer
 */
class Issuer
{
    /**
     * @var array<int, string>
     */
    protected array $abilities = [];

    protected bool $init = false;

    protected bool $isRoot = false;

    protected bool $isAdmin = false;

    protected bool $isManager = false;

    protected bool $isUser = false;

    protected bool $isGuest = false;

    protected bool $hasAbilities = false;

    protected bool $hasPrivileges = false;

    protected bool $hasRoles = false;

    protected bool $hasSanctum = false;

    protected bool $useSanctum = false;

    protected bool $onlyUserAbilities = false;

    /**
     * @var array<int, string>
     */
    protected array $rootAccessGroups = [
        'root',
        'admin',
    ];

    /**
     * @var array<int, string>
     */
    protected array $rootAbilities = [
        '*',
        'root',
    ];

    public function useSanctum(): bool
    {
        return $this->useSanctum;
    }

    /**
     * @return array<int, string>
     */
    protected function compileAbilitiesByGroup(string $group): array
    {
        /**
         * @var array<int, string> $packages
         */
        $packages = config('playground-auth.packages');

        if (! empty(config('playground-auth.require.package_abilities'))) {
            if (! in_array('playground-auth', $packages)) {
                array_unshift($packages, 'playground-auth');
            }
        }

        /**
         * @var array<string, array<string, mixed>> $package_abilities
         */
        $package_abilities = [];

        /**
         * @var array<int, string> $abilities
         */
        $abilities = [];

        if (empty($group)) {
            // Set the lowest permission scheme for abilities.
            $group = 'guest';
        }

        if (! empty($packages) && is_array($packages)) {
            foreach ($packages as $package) {
                $config = config($package.'.abilities.'.$group);
                $package_abilities[$package] = is_array($config) ? $config : [];
            }
        }

        foreach ($package_abilities as $package => $list) {
            if (! empty($list)) {
                foreach ($list as $ability) {
                    if (empty($ability) || ! is_string($ability)) {
                        if (config('app.debug') && config('playground-auth.debug')) {
                            Log::debug(__METHOD__, [
                                'error' => 'Invalid abilities defined in package.',
                                '$ability' => $ability,
                                '$group' => $group,
                                '$package' => $package,
                                '$list' => $list,
                            ]);
                        }

                        continue;
                    }
                    if (in_array('deny', $abilities)) {
                        if ($package !== 'playground-auth') {
                            if (config('app.debug') && config('playground-auth.debug')) {
                                Log::debug(__METHOD__, [
                                    'error' => 'Only the playground-auth configuration may implement deny for a group.',
                                    '$ability' => $ability,
                                    '$group' => $group,
                                    '$package' => $package,
                                    '$list' => $list,
                                ]);
                            }

                            continue;
                        }
                        $abilities = [
                            'deny',
                        ];
                    }
                    if ($ability === 'deny') {
                        if ($package !== 'playground-auth') {
                            if (config('app.debug') && config('playground-auth.debug')) {
                                Log::debug(__METHOD__, [
                                    'error' => 'Only the playground-auth configuration may implement deny for a group.',
                                    '$ability' => $ability,
                                    '$group' => $group,
                                    '$package' => $package,
                                    '$list' => $list,
                                ]);
                            }

                            continue;
                        }
                        $abilities = [
                            'deny',
                        ];
                    }
                    if (in_array($ability, $this->rootAbilities)
                        && ! in_array($group, $this->rootAccessGroups)
                    ) {
                        if (config('app.debug') && config('playground-auth.debug')) {
                            Log::debug(__METHOD__, [
                                'error' => sprintf('Root abilites are limited to the groups: %1$s', implode(', ', $this->rootAccessGroups)),
                                '$ability' => $ability,
                                '$group' => $group,
                                '$package' => $package,
                                '$list' => $list,
                                '$this->rootAccessGroups' => $this->rootAccessGroups,
                                '$this->rootAbilities' => $this->rootAbilities,
                            ]);

                        }

                        continue;
                    }
                    if (! in_array($ability, $abilities)) {
                        $abilities[] = $ability;
                    }
                }
            }
        }

        return $abilities;
    }

    /**
     * @return array<int, string>
     */
    protected function abilitiesByGroup(string $group): array
    {
        return $this->compileAbilitiesByGroup($group);
    }

    /**
     * @return array<int, string>
     */
    protected function abilities(Authenticatable $user): array
    {
        $abilities = [];

        if ($this->onlyUserAbilities) {
            $abilities = [];
        } elseif ($this->isRoot) {
            $abilities = $this->abilitiesByGroup('root');
        } elseif ($this->isAdmin) {
            $abilities = $this->abilitiesByGroup('admin');
        } elseif ($this->isManager) {
            $abilities = $this->abilitiesByGroup('manager');
        } elseif ($this->isUser) {
            $abilities = $this->abilitiesByGroup('user');
        } elseif ($this->isGuest) {
            $abilities = $this->abilitiesByGroup('guest');
        }

        foreach ($abilities as $ability) {
            if (is_string($ability)
                && $ability
                && ! in_array($ability, $this->abilities)
            ) {
                $this->abilities[] = $ability;
            }
        }

        if (empty($this->abilities)) {
            // $this->abilities[] = 'deny';
        }

        return $this->abilities;
    }

    public function init(Authenticatable $user): self
    {
        if ($this->init) {
            return $this;
        }

        $config = config('playground-auth');
        $config = is_array($config) ? $config : [];

        if ($user instanceof HasApiTokens || is_callable([$user, 'createToken'])) {
            $this->hasSanctum = true;
        } else {
            $this->hasSanctum = false;
        }

        if ($this->hasSanctum && ! empty($config['sanctum'])) {
            $this->useSanctum = true;
        }
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$user' => $user->toArray(),
        //     '$config' => $config,
        // ]);

        if ($user instanceof Abilities) {
            if (empty($config['abilities'])) {
                $this->abilities = [];
                $this->onlyUserAbilities = false;
            } else {
                $abilities = $user->getAttributeValue('abilities');
                $this->abilities = is_array($abilities) ? $abilities : [];
                $this->onlyUserAbilities = $config['abilities'] === 'user';
            }
        } else {
            $this->abilities = [];
            $this->onlyUserAbilities = false;
        }

        if ($user instanceof Admin) {
            $this->isAdmin = $user->isAdmin();
        } else {
            $this->isAdmin = false;
        }

        if ($user instanceof Privileges) {
            $this->hasPrivileges = ! empty($config['privileges']);
        } else {
            $this->hasPrivileges = false;
        }

        if ($user instanceof Role) {
            $this->hasRoles = ! empty($config['roles']);
            if ($this->hasRoles) {
                $this->isRoot = $user->hasRole('root');
                if (! $this->isAdmin) {
                    $this->isAdmin = $user->hasRole('admin');
                }
                $this->isUser = $user->hasRole('user');
                $this->isManager = $user->hasRole('manager');
                if ($user->hasRole('guest')) {
                    $this->isGuest = true;
                }
            }
        } else {
            $this->hasRoles = false;
            $this->isRoot = false;
            $this->isUser = false;
            $this->isGuest = false;
        }

        if (empty($config['listed'])) {
            $this->listed($user);
        }

        if (! $this->isGuest) {
            $this->isGuest = ! ($this->isRoot || $this->isAdmin || $this->isManager || $this->isUser);
        }

        $this->init = true;

        return $this;
    }

    public function listed(Authenticatable $user): void
    {
        $email = $user->getAttributeValue('email');
        $managers = config('playground-auth.managers');
        if (is_array($managers)) {
            if ($email && in_array($email, $managers)) {
                $this->isManager = true;
            }
        }

        $admins = config('playground-auth.admins');
        if (is_array($admins)) {
            if ($email && in_array($email, $admins)) {
                $this->isAdmin = true;
            }
        }
    }

    /**
     * @return array<string, ?string> Returns tokens for authorization.
     */
    public function authorize(Authenticatable $user): array
    {
        $this->init($user);

        if ($this->hasSanctum && $this->useSanctum) {
            $tokens = $this->sanctum($user);
        } else {
            $tokens = [];
        }

        return $tokens;
    }

    /**
     * @return array<string, ?string>
     */
    public function sanctum(Authenticatable $user): array
    {
        /**
         * @var array<string, mixed> $config
         */
        $config = config('playground-auth.token');

        $this->init($user);

        $tokens = [];

        if (! $this->hasSanctum) {
            Log::debug(__('playground-auth::auth.sanctum.disabled'));

            return $tokens;
        }

        $name = 'app';
        if (! empty($config['name']) && is_string($config['name'])) {
            $name = $config['name'];
        }

        // https://github.com/laravel/sanctum/pull/498
        $expiresAt = null;
        if (! empty($config['expires']) && is_string($config['expires'])) {
            $expiresAt = Carbon::parse($config['expires']);
        }

        if (is_callable([$user, 'createToken'])) {
            $tokens[$name] = $user->createToken(
                $name,
                $this->abilities($user),
                $expiresAt
            )->plainTextToken;
        }

        return $tokens;
    }
}
