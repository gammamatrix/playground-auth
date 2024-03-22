<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Auth\Policies\PolicyTrait;

use Illuminate\Auth\Access\Response;
use Playground\Test\Models\User;
use Tests\Unit\Playground\Auth\TestCase;
use TiMacDonald\Log\LogEntry;
use TiMacDonald\Log\LogFake;

/**
 * \Tests\Unit\Playground\Auth\Policies\PolicyTrait\TraitTest
 */
class TraitTest extends TestCase
{
    public function test_getEntity(): void
    {
        $instance = new Policy;
        $this->assertSame('', $instance->getEntity());
    }

    public function test_getPackage(): void
    {
        $instance = new Policy;
        $this->assertSame('', $instance->getPackage());
    }

    public function test_hasToken(): void
    {
        $instance = new Policy;
        $this->assertFalse($instance->hasToken());
    }

    public function test_getToken(): void
    {
        $instance = new Policy;
        $this->assertNull($instance->getToken());
    }

    public function test_setToken(): void
    {
        $instance = new Policy;
        $this->assertIsObject($instance->setToken());
    }

    public function test_verify_does_not_log_when_app_debugging_is_disabled(): void
    {
        config([
            'app.debug' => false,
            'playground-auth.debug' => true,
        ]);

        $instance = new Policy;

        $log = LogFake::bind();

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $verify = 'invalid-verifier';

        config(['playground-auth.verify' => $verify]);

        $ability = 'view';

        $this->assertFalse($instance->verify($user, $ability));

        $log->assertNothingLogged();
    }

    public function test_verify_does_not_log_when_auth_debugging_is_disabled(): void
    {
        config([
            'app.debug' => true,
            'playground-auth.debug' => false,
        ]);

        $instance = new Policy;

        $log = LogFake::bind();

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $verify = 'invalid-verifier';

        config(['playground-auth.verify' => $verify]);

        $ability = 'view';

        $this->assertFalse($instance->verify($user, $ability));

        $log->assertNothingLogged();
    }

    public function test_verify_logs_with_debugging_enabled(): void
    {
        config([
            'app.debug' => true,
            'playground-auth.debug' => true,
        ]);

        $instance = new Policy;

        $log = LogFake::bind();

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $verify = 'invalid-verifier';

        config(['playground-auth.verify' => $verify]);

        $ability = 'view';

        $this->assertFalse($instance->verify($user, $ability));

        $log->assertLogged(
            fn (LogEntry $log) => $log->level === 'debug'
        );

        $log->assertLogged(
            fn (LogEntry $log) => str_contains(
                is_string($log->context['$ability']) ? $log->context['$ability'] : '',
                $ability
            )
        );
    }

    public function test_verify_privileges(): void
    {
        $instance = new Policy;

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $verify = 'privileges';

        config(['playground-auth.verify' => $verify]);

        $ability = 'view';

        $result = $instance->verify($user, $ability);

        $this->assertInstanceOf(Response::class, $result);
        // $this->assertFalse($instance->verify($user, $ability));
    }

    public function test_verify_roles(): void
    {
        $instance = new Policy;

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $verify = 'roles';

        config(['playground-auth.verify' => $verify]);

        $ability = 'view';

        $result = $instance->verify($user, $ability);

        $this->assertInstanceOf(Response::class, $result);
        // $this->assertFalse($result);
    }

    public function test_verify_user(): void
    {
        $instance = new Policy;

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $verify = 'user';

        config(['playground-auth.verify' => $verify]);

        $ability = 'view';

        $this->assertTrue($instance->verify($user, $ability));
    }
}
