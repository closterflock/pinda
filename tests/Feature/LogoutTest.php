<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Tests that a user is logged out successfully, gets redirected,
     * and is unable to access secure urls.
     */
    public function testLogoutSuccess()
    {
        $user = factory(User::class)->create();
        \Auth::setUser($user);

        $response = $this->post(route('logout'));
        //The initial response redirects back to home
        $response->assertRedirect('/');
        //User should no longer be authenticated after login.
        $this->assertNull(\Auth::user());

        //Subsequent requests to home should fail without login.
        $redirectedResponse = $this->get(route('home'));
        $redirectedResponse->assertRedirect(route('login'));
    }
}