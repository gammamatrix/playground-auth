<?php

declare(strict_types=1);
/**
 * Playground
 */

namespace Tests\Feature\Playground\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Unit\Playground\Auth\TestCase as BaseTestCase;

/**
 * \Tests\Feature\Playground\Auth\TestCase
 */
class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected bool $load_migrations_laravel = false;

    protected bool $load_migrations_playground = false;

    /**
     * Define database migrations.
     *
     * @api
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        if (! empty(env('TEST_DB_MIGRATIONS'))) {
            if ($this->load_migrations_laravel) {
                $this->loadPlaygroundMigration('migrations-laravel');
            }
            if ($this->load_migrations_playground) {
                $this->loadPlaygroundMigration('migrations-playground');
            }
        }
    }

    protected function loadPlaygroundMigration(string $folder): void
    {
        $playground_database = sprintf('%1$s/playground/database', dirname(dirname(dirname(__DIR__))));
        $migrations = sprintf('%1$s/%2$s', $playground_database, $folder);

        if ($folder && is_dir($playground_database) && is_dir($migrations)) {
            $this->loadMigrationsFrom($playground_database.'/'.$folder);
        }
    }
}
