<?php


namespace Tests\Feature;


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
        $this->stub();
    }
}