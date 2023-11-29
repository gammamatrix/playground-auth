<?php
/**
 * GammaMatrix
 */

namespace Tests\Feature\GammaMatrix\Playground\Auth\Http\Controllers;

use GammaMatrix\Playground\Test\Models\User;
use Tests\Feature\GammaMatrix\Playground\Auth\TestCase;

/**
 * \Tests\Feature\GammaMatrix\Playground\Auth\Http\Controllers\AuthenticationRouteTest
 *
 */
class AuthenticationRouteTest extends TestCase
{
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_users_cannot_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_users_can_logout_on_get_request()
    {
        $user = User::factory()->create();
        // dd([
        //     'playground' => config('playground'),
        //     'playground-auth' => config('playground-auth'),
        // ]);
        $response = $this->actingAs($user)->get('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_users_can_logout_on_post_request()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_guests_can_logout_on_get_request()
    {
        $response = $this->get('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function tesst_guests_can_logout_on_post_request()
    {
        $response = $this->get('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_csfr_token_request()
    {
        $response = $this->get('/token');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'meta' => [
                'token',
            ],
        ]);
    }

    public function test_login_repeat_under_rate_limit_and_clear()
    {
        $user = User::factory()->create();

        $limit = 2;

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
            $response->assertStatus(302);
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_login_repeat_and_hit_rate_limit()
    {
        $user = User::factory()->create();

        $limit = 6;

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
            $response->assertStatus(302);
        }

        // 'Too many login attempts. Please try again in 59 seconds.'
        $response->assertSessionHasErrors([
            'email',
        ]);
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();
    }
}
