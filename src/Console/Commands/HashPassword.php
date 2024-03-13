<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Auth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

/**
 * \Playground\Auth\Console\Commands\HashPassword
 */
class HashPassword extends Command
{
    /**
     * @var string The console command name.
     */
    protected $signature = 'auth:hash-password
        {password? : The password to hash.}
        {--pretty : Format the JSON output}
        {--json : Output the result as JSON}';

    /**
     * @var string The console command description.
     */
    protected $description = 'Hash a password.';

    /**
     * @var bool Return a JSON response.
     */
    protected $json = false;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->json = $this->option('json');

        if ($this->hasArgument('password')) {
            $password = $this->argument('password');
        } else {
            $password = password('Please provide the password to hash:');
        }

        $hashed = $password && is_string($password) ? Hash::make($password) : '';

        if (! $this->json && $hashed) {
            $this->line(PHP_EOL);
            $this->comment($hashed);
        }

        if ($this->json) {
            $output = json_encode(
                ['hashed' => $hashed],
                $this->option('pretty') ? JSON_PRETTY_PRINT : 0
            );
            if ($output) {
                $this->line($output);
            }
        } else {
            $this->line(PHP_EOL);
        }
    }
}
