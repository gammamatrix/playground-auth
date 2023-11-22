<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Auth\Http\Controllers;

use GammaMatrix\Playground\Auth\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
     *
     * TODO This should work with any kind of authentication system. Identify what is supported.
     *
     * Types:
     * - User::$priviliges
     * - User::hasPrivilige()
     * - User::$roles
     * - User::hasRole() - with string or array?
     * - User::hasRoles()
     * - Auth::user()?->currentAccessToken()?->can('app:*')
     * - Auth::user()?->currentAccessToken()?->can($withPrivilege.':create')
     *
     * @experimental Subject to change
     */
    protected function privileges(Authenticatable $user): array
    {
        $privileges = [];

        $hasRoles = !empty(config('playground-auth.token.roles'));

        $isAdmin = $hasRoles && $user->hasRole(['admin', 'wheel', 'root']);
        $isManager = $hasRoles && $user->hasRole(['amanager']);

        $managers = config('playground-auth.managers');
        if (is_array($managers)) {
            if ($user->email && in_array($user->email, $managers)) {
                $isAdmin = false;
                $isManager = true;
            }
        }

        $admins = config('playground-auth.admins');
        if (is_array($admins)) {
            if ($user->email && in_array($user->email, $admins)) {
                $isAdmin = true;
                $isManager = false;
            }
        }

        if ($isAdmin) {
            $privileges_admin = config('playground-auth.privileges.admin');
            if (is_array($privileges_admin)) {
                foreach ($privileges_admin as $privilege) {
                    if (is_string($privilege)
                        && $privilege
                        && !in_array($privilege, $privileges)
                    ) {
                        $privileges[] = $privilege;
                    }
                }
            }
        } elseif ($isManager) {
            $privileges_manager = config('playground-auth.privileges.manager');
            if (is_array($privileges_manager)) {
                foreach ($privileges_manager as $privilege) {
                    if (is_string($privilege)
                        && $privilege
                        && !in_array($privilege, $privileges)
                    ) {
                        $privileges[] = $privilege;
                    }
                }
            }
        } else {
            $privileges_user = config('playground-auth.privileges.user');
            if (is_array($privileges_user)) {
                foreach ($privileges_user as $privilege) {
                    if (is_string($privilege)
                        && $privilege
                        && !in_array($privilege, $privileges)
                    ) {
                        $privileges[] = $privilege;
                    }
                }
            }
        }

        return $privileges;
    }

    /**
     *
     * NOTE: Creates multiple keys. Not sure if it is ok to reuse a token?
     * TODO: This needs the device_name handling for Sanctum
     */
    protected function issue(Request $request): array
    {
        $user = $request->user();

        $tokens = [];

        $name = config('playground-auth.token.name');

        $privileges = $this->privileges($user);

        $expiresAt = new Carbon(config('playground-auth.token.expires'));



        // use Laravel\Sanctum\PersonalAccessToken;

        // $token = PersonalAccessToken::findToken($hashedTooken);


        // dd([
        //     '__METHOD__' => __METHOD__,
        //     'createToken' => $user->createToken($name, $privileges, $expiresAt)->toArray(),
        // ]);
        $tokens[$name] = $user->createToken($name, $privileges, $expiresAt)->plainTextToken;

        return $tokens;
    }

    /**
     * Authenticated the user.
     *
     * @route POST /login
     */
    public function store(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $request->authenticate();
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     // '$request' => $request,
        // ]);

        $request->session()->regenerate();

        $payload = [
            'message' => __('authenticated'),
            'tokens'  => [],
        ];

        if (!empty(config('playground-auth.token.sanctum'))) {
            $payload['tokens'] = $this->issue($request);
        }

        if (!empty(config('playground-auth.session'))) {
            $payload['tokens']['session'] = $request->session()->token();
        }

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

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
        $all = $request->has('all') || $request->has('everywhere');

        if (!empty(config('playground-auth.token.sanctum'))) {
            $user = $request->user();

            if ($user) {
                if ($all) {
                    $user->tokens()->delete();
                } else {
                    $token = $user->currentAccessToken();
                    if ($token && is_callable($token, 'delete')) {
                        $token->delete();
                    }
                }
            }
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('logout'),
                'session_token' => $request->session()->token(),
            ]);
        }

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
