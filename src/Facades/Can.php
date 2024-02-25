<?php
/**
 * Playground
 */
namespace Playground\Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * \Playground\Auth\Facades\Can
 */
class Can extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'playground-auth-can';
    }
}
