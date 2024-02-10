<?php
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
    public function test_getRolesForAdmin(): void
    {
        $instance = new RoleModelPolicy;

        $expected = [
            'admin',
            'wheel',
            'root',
        ];

        $this->assertSame($expected, $instance->getRolesForAdmin());
    }

    public function test_getRolesForAction(): void
    {
        $instance = new RoleModelPolicy;

        $expected = [
            'admin',
            'wheel',
            'root',
        ];

        $this->assertSame($expected, $instance->getRolesForAction());
    }

    public function test_getRolesToView(): void
    {
        $instance = new RoleModelPolicy;

        $expected = [
            'admin',
            'wheel',
            'root',
        ];

        $this->assertSame($expected, $instance->getRolesToView());
    }

    public function test_hasRole(): void
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

    public function test_hasRole_advanced_role(): void
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
