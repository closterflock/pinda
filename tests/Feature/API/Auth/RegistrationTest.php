<?php


namespace Tests\Feature\API\Auth;


use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function testRegistrationNoCredentials()
    {
        $this->stub();
    }

    public function testRegistrationValidationFails()
    {
        $this->stub();
    }

    public function testRegistrationExistingUser()
    {
        $this->stub();
    }

    public function testRegistrationSuccess()
    {
        $this->stub();
    }

    private function makeLoginRequest($email = null, $name = null, $password = null): TestResponse
    {
        $params = [];
        if (!is_null($email)) {
            $params['email'] = $email;
        }

        if (!is_null($name)) {
            $params['name'] = $name;
        }

        if (!is_null($password)) {
            $params['password'] = $password;
        }

        return $this->postJson(route('api.register'), $params, [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
    }
}