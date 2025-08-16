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
}
