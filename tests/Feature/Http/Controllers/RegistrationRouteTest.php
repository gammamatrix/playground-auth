<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * \Tests\Feature\Http\Controllers\RegistrationRouteTest
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
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$response' => $response,
        // ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }
}
