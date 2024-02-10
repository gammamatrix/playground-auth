<?php
/**
 * Playground
 */
namespace Playground\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
// use Illuminate\Support\Facades\Auth;
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
     * @return array<int, string>
     */
    protected function abilitiesByGroup(string $group): array
    {
        $abilities = config('playground-auth.abilities.'.$group);

        return is_array($abilities) ? $abilities : [];
    }

    /**
     * TODO This should work with any kind of authentication system. Identify what is supported.
     *
     * Types:
     * - User::$priviliges
     * - User::hasPrivilige()
     * - User::$roles
     * - User::hasRole() - with string or array?
     * - User::hasRoles()
     * - Auth::user()?->currentAccessToken()?->can('app:*')
     * - Auth::user()?->currentAccessToken()?->can($withPrivilege.':create')
     *
     * @experimental Subject to change
     *
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
            $this->abilities[] = 'none';
        }

        return $this->abilities;
    }

    /**
     * @param array<string, mixed> $config
     */
    public function init(Authenticatable $user, array $config): void
    {
        if ($user instanceof HasApiTokens) {
            $this->hasSanctum = ! empty($config['sanctum']);
        } else {
            $this->hasSanctum = false;
        }

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
                $this->isGuest = ! ($this->isRoot || $this->isAdmin || $this->isManager || $this->isUser);
            }
        } else {
            $this->hasRoles = false;
            $this->isRoot = false;
            $this->isUser = false;
            $this->isGuest = false;
        }

        if (! empty($config['listed'])) {
            $this->listed($user);
        }
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
     * @param Authenticatable&HasApiTokens $user
     * @return array<string, ?string>
     */
    public function sanctum(HasApiTokens $user): array
    {
        /**
         * @var array<string, mixed> $config
         */
        $config = config('playground-auth.token');

        $this->init($user, $config);

        if (! $this->hasSanctum) {
            throw new \Exception(__('playground-auth:auth.sanctum.disabled'));
        }

        $tokens = [];

        $name = 'app';
        if (! empty($config['name']) && is_string($config['name'])) {
            $name = $config['name'];
        }

        // $expiresAt = null;
        // if (! empty($config['expires']) && is_string($config['expires'])) {
        //     $expiresAt = new Carbon($config['expires']);
        // }

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     'createToken' => $user->createToken($name, $abilities, $expiresAt)->toArray(),
        // ]);
        $tokens[$name] = $user->createToken(
            $name,
            $this->abilities($user)
        )->plainTextToken;

        return $tokens;
    }
}
