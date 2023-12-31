<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Feature\GammaMatrix\Playground\Auth\Http\Controllers;

use Tests\Feature\GammaMatrix\Playground\Auth\TestCase;

/**
 * \Tests\Feature\GammaMatrix\Playground\Auth\Http\Controllers\RegistrationRouteTest
 *
 */
class RegistrationRouteTest extends TestCase
{
    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => $this->faker()->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }
}
