<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Auth\Console\Commands\HashPassword;

// use Illuminate\Support\Facades\Artisan;
use Tests\Unit\Playground\Auth\TestCase;

/**
 * \Tests\Unit\Playground\Auth\Console\Commands\HashPassword\CommandTest
 */
class CommandTest extends TestCase
{
    public function test_command_auth_hash_password_with_json(): void
    {
        // $result = $this->withoutMockingConsoleOutput()->artisan('auth:hash-password --json "my-password"');
        // dump(Artisan::output());
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('auth:hash-password --json "my-password"');
        $result->assertExitCode(0);
        $result->expectsOutputToContain('hashed');
    }

    public function test_command_auth_hash_password(): void
    {
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('auth:hash-password some-passord');
        $result->assertExitCode(0);
    }

    public function test_command_auth_hash_password_without_argument_and_fail(): void
    {
        // $result = $this->withoutMockingConsoleOutput()->artisan('auth:hash-password --no-interaction');
        // dump(Artisan::output());
        // $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);
        // $this->expectExceptionMessage('Not enough arguments (missing: "password").');

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('auth:hash-password --no-interaction');
        $result->assertExitCode(0);
        $result->doesntExpectOutputToContain('hashed');
    }
}
