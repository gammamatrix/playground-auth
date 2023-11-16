<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Auth\Http\Controllers;

use GammaMatrix\Playground\Auth\Http\Requests\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * \GammaMatrix\Playground\Auth\Http\Controllers\EmailVerificationController
 *
 */
class EmailVerificationController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @route GET /verify-email verification.notice
     */
    public function show(Request $request): Response|JsonResponse|RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->getRedirectUrl());
        }

        $package_config = config('playground');
        $package_config_auth = config('playground-auth');

        return view(sprintf('%1$s%2$s', $package_config_auth['view'], 'verify-email'), [
            'package_config' => $package_config,
            'package_config_auth' => $package_config_auth,
        ]);
    }

    /**
     * Send a new email verification notification.
     *
     * @route POST /verify-email verification.send
     */
    public function send(Request $request): RedirectResponse|Response
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->getRedirectUrl());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @route POST /verify-email/{id}/{hash} verification.verify
     */
    public function verify(
        EmailVerificationRequest $request
    ): Response|JsonResponse|RedirectResponse|View {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->getRedirectUrl().'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($this->getRedirectUrl().'?verified=1');
    }
}
