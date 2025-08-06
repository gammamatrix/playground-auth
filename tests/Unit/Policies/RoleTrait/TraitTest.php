<?php

declare(strict_types=1);
/**
 * Playground
 */

namespace Tests\Unit\Playground\Auth\Policies\RoleTrait;

use Illuminate\Auth\Access\Response;
use Playground\Test\Models\User;
use Tests\Unit\Playground\Auth\TestCase;

// use Playground\Auth\Policies\RoleTrait;

/**
 * \Tests\Unit\Playground\Policies\Auth\RoleTrait\TraitTest
 */
class TraitTest extends TestCase
{
    public function test_get_roles_for_admin(): void
    {
        $instance = new RoleModelPolicy;

        $expected = [
            'admin',
            'wheel',
            'root',
        ];

        $this->assertSame($expected, $instance->getRolesForAdmin());
    }

    public function test_get_roles_for_action(): void
    {
        $instance = new RoleModelPolicy;

        $expected = [
            'admin',
            'wheel',
            'root',
        ];

        $this->assertSame($expected, $instance->getRolesForAction());
    }

    public function test_get_roles_to_view(): void
    {
        $instance = new RoleModelPolicy;

        $expected = [
            'admin',
            'wheel',
            'root',
        ];

        $this->assertSame($expected, $instance->getRolesToView());
    }

    public function test_has_role(): void
    {
        $instance = new RoleModelPolicy;

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $ability = 'edit';
        $this->assertInstanceOf(Response::class, $instance->hasRole(
            $user,
            $ability
        ));
    }

    public function test_has_role_advanced_role(): void
    {
        $instance = new RoleModelPolicy;

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $ability = 'some-advanded-role';
        $this->assertInstanceOf(Response::class, $instance->hasRole(
            $user,
            $ability
        ));
    }
}
