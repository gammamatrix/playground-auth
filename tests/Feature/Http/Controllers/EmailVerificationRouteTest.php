<?php
/**
 * GammaMatrix
 */

namespace Tests\Feature\Http\Controllers;

use GammaMatrix\Playground\Test\Models\User;
use Tests\TestCase;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

/**
 * \Tests\Feature\Http\Controllers\EmailVerificationRouteTest
 *
 */
class EmailVerificationRouteTest extends TestCase
{
    public function test_email_verification_screen_can_be_rendered()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_verification_screen_is_not_rendered_if_already_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(302);
    }

    public function test_json_send_email_verification_notification_as_guest()
    {
        Notification::fake();

        $response = $this->json('post', '/verify-email');
        $response->assertStatus(401);

        // $response->dump();

        $response->assertJsonStructure([
            'message',
        ]);

        $response->assertExactJson([
            'message' => 'Unauthenticated.',
        ]);

        Notification::assertNothingSent();
    }

    public function test_send_email_verification_notification_as_guest_and_redirect()
    {
        Notification::fake();

        $response = $this->post('/verify-email');
        $response->assertStatus(302);
        $response->assertredirect('/login');

        // $response->dump();

        Notification::assertNothingSent();
    }

    public function test_send_email_verification_notification_as_user()
    {
        Notification::fake();
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/verify-email');

        // $response->dump();

        $response->assertStatus(302);

        $response->assertSessionHas('status', 'verification-link-sent');

        // Notification::assertNothingSent();
        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }

    /**
     * EmailVerificationController::send().
     *
     * @return void
     */
    public function test_send_email_verification_notification_when_already_verified()
    {
        Notification::fake();
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->post('/verify-email');

        // $response->dump();

        $response->assertStatus(302);

        Notification::assertNothingSent();
    }

    public function test_email_can_be_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id'   => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect('/?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id'   => $user->id,
                'hash' => sha1($user->email . 'make-this-invalid'),
            ]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_email_is_not_verified_with_invalid_user_id()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id'   => $user->id . 'make-this-invalid',
                'hash' => sha1($user->email),
            ]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_email_verified_with_already_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id'   => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertNotDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect('/?verified=1');
    }
}
