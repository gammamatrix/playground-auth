<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Auth\Console\Commands\About;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Auth\ServiceProvider;
use Playground\ServiceProvider as PlaygroundServiceProvider;
use Playground\Test\OrchestraTestCase;

/**
 * \Tests\Feature\Playground\Auth\Console\Commands\About
 */
#[CoversClass(ServiceProvider::class)]
class CommandTest extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PlaygroundServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    public function test_command_about_displays_package_information_and_succeed_with_code_0(): void
    {
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('about');
        $result->assertExitCode(0);
        $result->expectsOutputToContain('Playground: Auth');
    }
}
