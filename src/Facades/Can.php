<?php
/**
 * Playground
 */
namespace Playground\Auth\Facades;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;
use Playground\Auth\Permission;

/**
 * \Playground\Auth\Facades\Can
 *
 * @method static Permission access(?Authenticatable $user, array $_privileges)
 * @method static array<string, Permission> map(array $privileges, ?Authenticatable $user)
 * @method static string withPrivilege(array $meta)
 */
class Can extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'playground-auth-can';
    }
}
