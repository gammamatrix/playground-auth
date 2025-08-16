<?php

declare(strict_types=1);
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
 * @method static Permission access(?Authenticatable $user, array<string, mixed> $options)
 * @method static array<string, Permission> map(array<string, mixed> $privileges, ?Authenticatable $user)
 * @method static string withPrivilege(array<string, mixed> $meta)
 */
class Can extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'playground-auth-can';
    }
}
