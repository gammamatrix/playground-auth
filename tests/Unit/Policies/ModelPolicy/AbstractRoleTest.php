<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Auth\Policies\ModelPolicy;

use Illuminate\Auth\Access\Response;
use Playground\Test\Models\UserWithRoleAndRoles;
use Tests\Unit\Playground\Auth\TestCase;

/**
 * \Tests\Unit\Playground\Auth\Policies\ModelPolicy\AbstractRoleTest
 */
class AbstractRoleTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'playground.auth.verify' => 'roles',
            'playground.auth.hasRole' => true,
            'playground.auth.userRoles' => true,
        ]);
    }

    public function test_create_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $this->assertInstanceOf(Response::class, $instance->create($user));
    }

    public function test_create_with_admin(): void
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

        $this->assertTrue($instance->create($user));
    }

    public function test_delete_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->delete($user, $model));
    }

    public function test_delete_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->delete($user, $model));
    }

    public function test_delete_locked_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $model->setAttribute('locked', true);

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $response = $instance->delete($user, $model);

        $this->assertInstanceOf(
            Response::class,
            $response
        );

        $this->assertFalse($response->allowed());
        $this->assertTrue($response->denied());
        $this->assertSame(423, $response->status());

        // These could be customized:
        // $this->assertNull($response->code());
        // $this->assertNull($response->message());
    }

    public function test_detail_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->detail($user, $model));
    }

    public function test_detail_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->detail($user, $model));
    }

    public function test_edit_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->edit($user, $model));
    }

    public function test_edit_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->edit($user, $model));
    }

    public function test_forceDelete_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->forceDelete($user, $model));
    }

    public function test_forceDelete_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->forceDelete($user, $model));
    }

    public function test_lock_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->lock($user, $model));
    }

    public function test_lock_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->lock($user, $model));
    }

    public function test_manage_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->manage($user, $model));
    }

    public function test_manage_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->manage($user, $model));
    }

    public function test_restore_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->restore($user, $model));
    }

    public function test_restore_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->restore($user, $model));
    }

    public function test_store_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $this->assertInstanceOf(Response::class, $instance->store($user));
    }

    public function test_store_with_admin(): void
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
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->store($user));
    }

    public function test_update_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->update($user, $model));
    }

    public function test_update_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->update($user, $model));
    }

    public function test_update_locked_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $model->setAttribute('locked', true);

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $response = $instance->update($user, $model);

        $this->assertInstanceOf(
            Response::class,
            $response
        );

        $this->assertFalse($response->allowed());
        $this->assertTrue($response->denied());
        $this->assertSame(423, $response->status());

        // These could be customized:
        // $this->assertNull($response->code());
        // $this->assertNull($response->message());
    }

    public function test_unlock_without_role(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $this->assertInstanceOf(Response::class, $instance->unlock($user, $model));
    }

    public function test_unlock_with_admin(): void
    {
        $instance = new TestPolicy;

        /**
         * @var UserWithRoleAndRoles $user
         */
        $user = UserWithRoleAndRoles::factory()->make();

        $model = new UserWithRoleAndRoles;

        $role = 'admin';
        $roles = [
            'root',
        ];

        $user->setAttribute('role', $role);
        $user->setAttribute('roles', $roles);

        $this->assertTrue($instance->unlock($user, $model));
    }
}
