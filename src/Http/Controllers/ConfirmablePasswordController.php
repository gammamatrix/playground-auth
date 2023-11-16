<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * \GammaMatrix\Playground\Auth\Http\Controllers\ConfirmablePasswordController
 *
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @route GET /confirm-password password.confirm
     */
    public function show(): View
    {
        $package_config = config('playground');
        $package_config_auth = config('playground-auth');

        return view(sprintf('%1$s%2$s', $package_config_auth['view'], 'confirm-password'), [
            'package_config' => $package_config,
            'package_config_auth' => $package_config_auth,
        ]);
    }

    /**
     * Confirm the user's password.
     *
     * @route POST /confirm-password
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended($this->getRedirectUrl());
    }
}
