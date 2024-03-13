<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Auth\Policies\RoleTrait;

use Playground\Auth\Policies\ModelPolicy;

/**
 * \Tests\Unit\Playground\Auth\Policies\RoleTrait\RoleModelPolicy
 */
class RoleModelPolicy extends ModelPolicy
{
    protected string $entity = 'user';

    protected string $package = 'playground-auth';
}
