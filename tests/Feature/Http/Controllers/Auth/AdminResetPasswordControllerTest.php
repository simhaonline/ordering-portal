<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\AdminResetPasswordController
 */
class AdminResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function reset_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->post(route('cms.password.update'), [
            // TODO: send request data
        ]);

        $response->assertOk();
        $response->assertViewIs('authentication.passwords.reset');
        $response->assertViewHas('token');
        $response->assertViewHas('email');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_reset_form_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('cms.password.reset', ['token' => $token]));

        $response->assertOk();
        $response->assertViewIs('authentication.passwords.reset');
        $response->assertViewHas('token');
        $response->assertViewHas('email');

        // TODO: perform additional assertions
    }

    // test cases...
}
