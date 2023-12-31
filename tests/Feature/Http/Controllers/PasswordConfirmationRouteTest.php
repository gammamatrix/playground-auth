<?php
/**
 * GammaMatrix
 */

namespace Tests\Feature\GammaMatrix\Playground\Auth\Http\Controllers;

use GammaMatrix\Playground\Test\Models\User;
use Tests\Feature\GammaMatrix\Playground\Auth\TestCase;

/**
 * \Tests\Feature\GammaMatrix\Playground\Auth\Http\Controllers\PasswordConfirmationRouteTest
 *
 */
class PasswordConfirmationRouteTest extends TestCase
{
    public function test_confirm_password_screen_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
