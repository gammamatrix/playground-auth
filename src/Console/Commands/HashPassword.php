<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Auth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * \GammaMatrix\Playground\Auth\Console\Commands\HashPassword
 *
 */
class HashPassword extends Command
{
    /**
     * @var string The console command name.
     */
    protected $signature = 'auth:hash-password
        {password : The password to hash.}
        {--pretty : Format the JSON output}
        {--json : Output the result as JSON}'
    ;

    /**
     * @var string The console command description.
     */
    protected $description = 'Hash a password.';

    /**
     * @var boolean Return a JSON response.
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
        $password = Hash::make($this->argument('password'));

        if (!$this->json) {
            $this->line(PHP_EOL);
            $this->comment($password);
        }

        if ($this->json) {
            $this->line(json_encode(
                ['password' => $password],
                $this->option('pretty') ? JSON_PRETTY_PRINT : null
            ));
        } else {
            $this->line(PHP_EOL);
        }
    }
}
