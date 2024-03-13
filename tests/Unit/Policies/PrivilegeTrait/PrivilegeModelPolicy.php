<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Auth\Policies\PrivilegeTrait;

use Playground\Auth\Policies\ModelPolicy;

/**
 * \Tests\Unit\Playground\Auth\Policies\PrivilegeTrait\PrivilegeModelPolicy
 */
class PrivilegeModelPolicy extends ModelPolicy
{
    protected string $package = 'playground-auth';

    protected string $entity = 'user';
}
