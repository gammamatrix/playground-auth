<?php
/**
 * Playground
 */
namespace Playground\Auth\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * \Playground\Auth\Policies\PolicyTrait
 */
trait PolicyTrait
{
    /**
     * @var bool Allow root to override rules in before().
     */
    protected $allowRootOverride = true;

    protected string $package = '';

    protected string $entity = '';

    protected ?PersonalAccessToken $token = null;

    abstract public function hasPrivilege(Authenticatable $user, string $privilege): bool|Response;

    abstract public function hasRole(Authenticatable $user, string $ability): bool|Response;

    abstract public function privilege(string $ability = '*'): string;

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function getPackage(): string
    {
        return $this->package;
    }

    public function hasToken(): bool
    {
        return ! empty($this->token);
    }

    public function getToken(): ?PersonalAccessToken
    {
        return $this->token;
    }

    public function setToken(PersonalAccessToken $token = null): self
    {
        $this->token = $token;

        return $this;
    }

    public function verify(Authenticatable $user, string $ability): bool|Response
    {
        $verify = config('playground.auth.verify');
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$verify' => $verify,
        //     '$ability' => $ability,
        //     '$user' => $user,
        // ]);
        if ($verify === 'privileges') {
            return $this->hasPrivilege($user, $this->privilege($ability));
        } elseif ($verify === 'roles') {
            return $this->hasRole($user, $ability);
        } elseif ($verify === 'user') {
            // A user with an email address passes.
            return ! empty($user->getAttribute('email'));
        }
        Log::debug(__METHOD__, [
            '$ability' => $ability,
            '$user' => $user,
        ]);

        return false;
    }
}
