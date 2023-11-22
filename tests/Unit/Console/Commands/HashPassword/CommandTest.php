<?php
/**
 * GammaMatrix
 */

namespace Tests\Unit\GammaMatrix\Playground\Auth\Console\Commands\HashPassword;

use Tests\Unit\GammaMatrix\Playground\Auth\TestCase;

/**
 * \Tests\Unit\GammaMatrix\Playground\Auth\Console\Commands\HashPassword\CommandTest
 *
 */
class CommandTest extends TestCase
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
