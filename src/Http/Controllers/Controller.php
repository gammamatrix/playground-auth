<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Auth\Http\Controllers;

use GammaMatrix\Playground\Http\Controllers\Controller as BaseController;

/**
 * \GammaMatrix\Playground\Auth\Http\Controllers\Controller
 *
 */
abstract class Controller extends BaseController
{
    /**
     * Get the redirect path for authentication.
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        $path = config('playground-auth.redirect');
        return is_string($path) ? $path : '';
    }
}
