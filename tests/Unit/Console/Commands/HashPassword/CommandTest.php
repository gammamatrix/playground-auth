<?php
/**
 * GammaMatrix
 */

namespace Tests\Unit\Console\Commands\HashPassword;

/**
 * \Tests\Unit\Console\Commands\HashPassword\CommandTest
 *
 */
class CommandTest extends \Tests\TestCase
{
    public function test_command_auth_hash_password_with_json()
    {
        $result = $this->artisan('auth:hash-password --json "my-password"')
            ->assertExitCode(0)
            ->expectsOutputToContain('password')
        ;
    }

    public function test_command_auth_hash_password()
    {
        $this->artisan('auth:hash-password some-passord')
            ->assertExitCode(0)
        ;
    }

    public function test_command_auth_hash_password_without_argument_and_fail()
    {
        $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "password").');

        $this->artisan('auth:hash-password')
            ->assertExitCode(1)
        ;
    }
}
