<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Auth\Http\Controllers;

use GammaMatrix\Playground\Auth\Http\Requests\PasswordResetRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * \GammaMatrix\Playground\Auth\Http\Controllers\PasswordResetLinkController
 *
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     */
    public function create(): View
    {
        $package_config = config('playground');
        $package_config_auth = config('playground-auth');

        return view(sprintf('%1$s%2$s', $package_config_auth['view'], 'forgot-password'), [
            'package_config' => $package_config,
            'package_config_auth' => $package_config_auth,
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PasswordResetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $status = Password::sendResetLink($validated);

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
