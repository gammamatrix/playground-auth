<?php

declare(strict_types=1);
/**
 * Playground
 */

namespace Tests\Unit\Playground\Auth\Policies\PrivilegeTrait;

use Illuminate\Auth\Access\Response;
use Playground\Test\Models\UserWithRoleAndRolesAndPrivileges;
use Playground\Test\Models\UserWithSanctum;
use Tests\Unit\Playground\Auth\TestCase;

// use Playground\Auth\Policies\PrivilegeTrait;
// use Illuminate\Contracts\Auth\Authenticatable;
// use Laravel\Sanctum\Contracts\HasApiTokens;

/**
 * \Tests\Unit\Playground\Auth\Policies\PrivilegeTrait\TraitTest
 */
class TraitTest extends TestCase
{
    public function test_privilege_privilege_policy_without_parameter(): void
    {
        $instance = new PrivilegePolicy;

        $expected = '*';

        $this->assertSame($expected, $instance->privilege());
    }

    public function test_privilege_privilege_model_policy_without_parameter(): void
    {
        $instance = new PrivilegeModelPolicy;

        $expected = 'playground-auth:user:*';

        $this->assertSame($expected, $instance->privilege());
    }

    public function test_privilege_user_policy_without_parameter(): void
    {
        $instance = new UserPolicy;

        $expected = '*';

        $this->assertSame($expected, $instance->privilege());
    }

    public function test_privilege_with_package_and_without_parameter(): void
    {
        $instance = new PrivilegeModelPolicy;

        $expected = 'playground-auth:user:*';

        $this->assertSame($expected, $instance->privilege());
    }

    public function test_privilege_with_package_and_entity_and_without_parameter(): void
    {
        $instance = new PrivilegeModelPolicy;

        $expected = 'playground-auth:user:*';

        $this->assertSame($expected, $instance->privilege());
    }

    public function test_has_privilege(): void
    {
        $instance = new PrivilegeModelPolicy;

        config(['playground-auth.sanctum' => true]);

        /**
         * @var UserWithSanctum $user
         */
        $user = UserWithSanctum::factory()->make();

        $privilege = '';

        $this->assertInstanceOf(Response::class, $instance->hasPrivilege(
            $user,
            $privilege
        ));
    }

    public function test_has_privilege_with_user_has_privilege(): void
    {
        $instance = new PrivilegeModelPolicy;

        config([
            'playground-auth.hasPrivilege' => true,
            'playground-auth.sanctum' => false,
        ]);

        /**
         * @var UserWithRoleAndRolesAndPrivileges $user
         */
        $user = UserWithRoleAndRolesAndPrivileges::factory()->make([
            'privileges' => ['quack'],
        ]);
        $privilege = 'quack';

        $this->assertTrue($instance->hasPrivilege(
            $user,
            $privilege
        ));
    }

    public function test_has_privilege_with_user_privileges(): void
    {
        $instance = new PrivilegeModelPolicy;

        config([
            'playground-auth.userPrivileges' => true,
            'playground-auth.sanctum' => false,
        ]);

        /**
         * @var UserWithRoleAndRolesAndPrivileges $user
         */
        $user = UserWithRoleAndRolesAndPrivileges::factory()->make([
            'privileges' => ['quack'],
        ]);
        $privilege = 'quack';

        $this->assertTrue($instance->hasPrivilege(
            $user,
            $privilege
        ));
    }

    public function test_has_privilege_without_privileges_enabled(): void
    {
        $instance = new PrivilegeModelPolicy;

        config([
            'playground-auth.hasPrivilege' => false,
            'playground-auth.userPrivileges' => false,
            'playground-auth.sanctum' => false,
        ]);

        /**
         * @var UserWithRoleAndRolesAndPrivileges $user
         */
        $user = UserWithRoleAndRolesAndPrivileges::factory()->make([
            'privileges' => ['quack'],
        ]);
        $privilege = 'quack';

        $this->assertInstanceOf(Response::class, $instance->hasPrivilege(
            $user,
            $privilege
        ));
    }
}
