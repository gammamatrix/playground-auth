<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Auth\Policies\Policy;

use Illuminate\Auth\Access\Response;
use Playground\Test\Models\UserWithRoleAndRoles;
use Tests\Unit\Playground\Auth\TestCase;

/**
 * \Tests\Unit\Playground\Auth\Policies\Policy\AbstractTest
 */
class AbstractTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'playground.auth.userRole' => true,
            'playground.auth.userRoles' => true,
            'playground.auth.verify' => 'roles',
        ]);
    }

    public function test_before_with_root(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $ability = 'edit';

        $role = 'root';

        $user->setAttribute('role', $role);

        $this->assertTrue($instance->before(
            $user,
            $ability
        ));
    }

    public function test_before_with_root_as_secondary_fails(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $ability = 'edit';

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);

        $this->assertNull($instance->before(
            $user,
            $ability
        ));
    }

    public function test_index_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $this->assertInstanceOf(Response::class, $instance->index($user));
    }

    public function test_index_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);

        $this->assertTrue($instance->index($user));
    }

    public function test_view_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $this->assertInstanceOf(Response::class, $instance->view($user));
    }

    public function test_view_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $role = 'admin';
        $roles = [
            'wheel',
            'user',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->view($user));
    }

    public function test_view_with_admin_in_roles(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $role = 'user-external';
        $roles = [
            'wheel',
            'user',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->view($user));
    }
}
