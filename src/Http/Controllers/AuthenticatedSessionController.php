<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Auth\Http\Controllers;

use GammaMatrix\Playground\Auth\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * \GammaMatrix\Playground\Auth\Http\Controllers\AuthenticatedSessionController
 *
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @route GET /login login
     */
    public function create(): View
    {
        $package_config = config('playground');
        $package_config_auth = config('playground-auth');

        return view(sprintf('%1$s%2$s', $package_config_auth['view'], 'login'), [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'package_config' => $package_config,
            'package_config_auth' => $package_config_auth,
        ]);
    }

    /**
     * Authenticated the user.
     *
     * @route POST /login
     */
    public function store(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // if ($request->expectsJson()) {
        //     return response()->json([
        //         'message' => __('authenticated'),
        //         'session_token' => $request->session()->token(),
        //     ]);
        // }

        return redirect()->intended($this->getRedirectUrl());
    }

    /**
     * Destroy an authenticated session.
     *
     * @route GET /logout logout
     * @route POST /logout
     */
    public function destroy(Request $request): JsonResponse|RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // if ($request->expectsJson()) {
        //     return response()->json([
        //         'message' => __('logout'),
        //         'session_token' => $request->session()->token(),
        //     ]);
        // }

        return redirect('/');
    }

    /**
     * Return a CSRF token.
     *
     */
    public function token(Request $request): JsonResponse
    {
        return response()->json([
            'meta' => [
                'token' => csrf_token(),
            ]
        ]);
    }
}
