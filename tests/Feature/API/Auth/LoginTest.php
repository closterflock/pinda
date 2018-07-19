<?php


namespace Tests\Feature\API\Auth;


use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testLoginNoCredentials()
    {
        $this->stub();
    }

    public function testLoginInvalidEmail()
    {
        $this->stub();
    }

    public function testLoginInvalidPassword()
    {
        $this->stub();
    }

    public function testLoginSuccess()
    {
        $this->stub();
    }
}