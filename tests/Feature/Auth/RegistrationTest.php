<?php


namespace Tests\Feature\Auth;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    /**
     * Tests that a registration attempt fails with no
     * credentials specified.
     */
    public function testRegistrationNoCredentials()
    {
        $this->stub();
    }

    /**
     * Tests that a registration attempt fails when
     * an email is already in use.
     */
    public function testRegistrationEmailInUse()
    {
        $this->stub();
    }

    /**
     * Tests that a registration attempt fails when
     * the two specified passwords do not match.
     */
    public function testRegistrationPasswordsDoNotMatch()
    {
        $this->stub();
    }

    /**
     * Tests that a registration attempt succeeds and
     * properly logs in a user.
     */
    public function testRegistrationSuccess()
    {
        $this->stub();
    }
}