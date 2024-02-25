<?php
/**
 * Playground
 */
namespace Playground\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Playground\Models\Contracts\Privileges;

/**
 * \Playground\Auth\Can
 *
 * NOTE:
 * - This class is allowed to be called in actions, jobs or views and should
 *   always be safe to use, without throwing exceptions, if possible.
 * - It could be called dozens or hundreds of times on a paginated API index listing.
 * - It is not the responsibility of this class to verify the credentials have expired or are invalid.
 *
 * WARNING: Use Gate and/or Policy middleware for the security layer.
 */
class Can
{
    /**
     * @var array<string, mixed>
     */
    protected array $implementations = [
        'admin' => [],
        'policy' => [],
        'privileges' => [],
        'roles' => [],
        'sanctum' => [],
        'user' => [],
    ];

    protected bool $init = false;

    protected string $verify;

    protected ?Authenticatable $user = null;

    protected ?PersonalAccessToken $sanctumToken = null;

    protected bool $noSanctumToken = false;

    protected bool $sanctum = false;

    protected bool $hasPrivilege = false;

    protected bool $userPrivileges = false;

    protected bool $hasRole = false;

    protected bool $userRole = false;

    protected bool $userRoles = false;

    protected bool $sessionToken = false;

    protected string $sessionTokenName = '';

    protected string $canDefault = '';

    protected function matches(?Authenticatable $user): bool
    {
        if (! $user && ! $this->user) {
            return true;
        }

        if (! $user && $this->user) {
            return false;
        }

        if ($user && ! $this->user) {
            return false;
        }

        $currentUserId = $this->user?->getAttribute('id');
        $userId = $user?->getAttribute('id');

        return $currentUserId && $currentUserId === $userId;
    }

    protected function init(?Authenticatable $user): self
    {
        if ($this->init && ! $this->matches($user)) {
            $this->init = false;
        }

        if ($this->init) {
            return $this;
        }

        $this->user = $user;
        $this->sanctumToken = null;
        $this->noSanctumToken = false;

        $config = config('playground-auth');
        $config = is_array($config) ? $config : [];

        $this->verify = '';

        if (! empty($config['verify'])
            && is_string($config['verify'])
            && array_key_exists($config['verify'], $this->implementations)
        ) {
            $this->verify = $config['verify'];
        }

        $this->sanctum = ! empty($config['sanctum']);
        $this->hasPrivilege = ! empty($config['hasPrivilege']);
        $this->userPrivileges = ! empty($config['userPrivileges']);
        $this->hasRole = ! empty($config['hasRole']);
        $this->userRole = ! empty($config['userRole']);
        $this->userRoles = ! empty($config['userRoles']);

        $this->canDefault = '';
        if (! empty($config['canDefault'])
            && is_string($config['canDefault'])
        ) {
            $this->canDefault = $config['canDefault'];
        }

        $this->sessionToken = false;
        $this->sessionTokenName = '';

        if (! empty($config['token'])
            && is_array($config['token'])
        ) {
            $this->sessionToken = ! empty($config['token']['session']);

            if (! empty($config['token']['session_name'])
                && is_string($config['token']['session_name'])
            ) {
                $this->sessionTokenName = $config['token']['session_name'];
            }
        }

        $this->init = true;

        return $this;
    }

    protected function reset(?Authenticatable $user): self
    {
        $this->init = false;

        $this->init($user);

        return $this;
    }

    public function isGuest(?Authenticatable $user): bool
    {
        $isGuest = empty($user);

        if ($this->userRole
            && is_callable([$user, 'hasRole'])
            && $user->hasRole('guest')
        ) {
            $isGuest = true;
        }

        return $isGuest;
    }

    /**
     * @param array<string, mixed> $_privileges
     * @return array<string, mixed>
     */
    public function map(array &$_privileges, ?Authenticatable $user): array
    {
        $this->init($user);

        foreach ($_privileges as $entity => $options) {
            if (is_array($_privileges[$entity])) {
                $_privileges[$entity]['allow'] = $this->access(
                    $user,
                    is_array($options) ? $options : []
                );
            }
        }

        return $_privileges;
    }

    /**
     * @return array<int, string>
     */
    public function wildcards(string $privilege): array
    {
        $wildcards = ['*'];

        if (empty($privilege) || $privilege === '*') {
            return $wildcards;
        }

        $stringable = Str::of($privilege);

        if (! $stringable->contains(':')) {
            return $wildcards;
        }

        $hasWildcard = $stringable->endsWith('*');

        $exploded = explode(':', $privilege);

        if ($hasWildcard) {
            array_pop($exploded);
        }

        $p = '';
        $wc = '';
        foreach ($exploded as $key) {
            if ($key && is_string($key)) {
                if ($p) {
                    $p .= ':';
                }
                $p .= $key;
                $wc = $p.':*';

                if (! in_array($wc, $wildcards)) {
                    $wildcards[] = $wc;
                }
            }
        }

        return $wildcards;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function access(?Authenticatable $user, array $options = []): bool
    {
        $this->init($user);

        $isGuest = $this->isGuest($this->user);

        if ($isGuest) {
            // Deny if guest is not permitted
            if (empty($options['guest'])) {
                return false;
            }
        }

        $hash = null;

        $any = ! empty($options['any']);

        $privilege = $this->canDefault;
        if (! empty($options['privilege']) && is_string($options['privilege'])) {
            $privilege = $options['privilege'];
        }

        /**
         * @var array<int, string> $roles
         */
        $roles = [];
        if (! empty($options['roles']) && is_array($options['roles'])) {
            foreach ($options['roles'] as $role) {
                if (! empty($role)
                    && is_string($role)
                    && ! in_array($role, $roles)
                ) {
                    $roles[] = $role;
                }
            }
        }

        if ($this->verify === 'sanctum') {

            if (empty($privilege)) {
                // A privilege is required for checking.
                return false;
            }

            if ($this->noSanctumToken) {
                return false;
            }

            if (! $this->sanctumToken && is_callable([$this->user, 'currentAccessToken'])) {

                // Check if the user already has their token assigned.
                $this->sanctumToken = $this->user->currentAccessToken();

                if (empty($this->sanctumToken)
                    && $this->sessionToken
                    && $this->sessionTokenName
                ) {
                    $hash = session($this->sessionTokenName);

                    if ($hash && is_string($hash)) {
                        $this->sanctumToken = PersonalAccessToken::findToken($hash);
                        if (! $this->sanctumToken) {
                            $this->noSanctumToken = true;
                        }
                    }
                }
            }

            if (! $this->sanctumToken) {
                return false;
            }

            // Check the provided privilege before wildcards.
            if ($this->sanctumToken->can($privilege)) {
                return true;
            }

            foreach ($this->wildcards($privilege) as $wildcard) {
                if ($wildcard && is_string($wildcard)) {
                    if ($this->sanctumToken->can($wildcard)) {
                        return true;
                    }
                }
            }

            return false;

        } elseif ($this->verify === 'policy') {
            if (is_callable([$this->user, 'can'])
                && $this->user->can($privilege)
            ) {
                return true;
            }
        } elseif ($this->verify === 'admin') {
            if (is_callable([$this->user, 'isAdmin'])
                && $this->user->isAdmin()
            ) {
                return true;
            }
        } elseif ($this->verify === 'privileges') {
            if (is_callable([$this->user, 'hasPrivilege'])
                && $this->user->hasPrivilege($privilege)
            ) {
                return true;
            }
        } elseif ($this->verify === 'roles') {
            $allowed = false;
            $denied = false;
            if (is_callable([$this->user, 'hasRole'])) {
                if ($any) {
                    foreach ($roles as $role) {
                        if ($this->user->hasRole($role)) {
                            $allowed = true;
                        }
                    }
                } else {
                    foreach ($roles as $role) {
                        if ($this->user->hasRole($role)) {
                            $allowed = true;
                        } else {
                            $denied = true;
                        }
                    }
                }
            }

            return $allowed && ! $denied;
        } elseif ($this->verify === 'user') {
            return ! $isGuest;
        }

        return false;
    }
}
