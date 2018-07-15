<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    const CORRECT_EMAIL = 'test@pinda.jamesspencemilwaukee.com';
    const CORRECT_PASSWORD = 'secret';

    /**
     * Tests that a guest user is redirected when trying to access
     * the success url.
     */
    public function testNotLoggedInRedirect()
    {
        $response = $this->get($this->getSuccessUrl());

        $response->assertRedirect($this->getLoginUrl());
    }

    /**
     * Tests that a login attempt without credentials fails.
     */
    public function testLoginNoCredentials()
    {
        $response = $this->post($this->getLoginSubmitUrl());
        $response->assertSessionHasErrors([
            'email', 'password'
        ]);
    }

    /**
     * Tests that a login attempt with an incorrect email address
     * fails.
     */
    public function testLoginIncorrectEmail()
    {
        $this->createUser();

        $response = $this->post($this->getLoginSubmitUrl(), [
            'email' => 'incorrectEmail@pinda.jamesspencemilwaukee.com',
            'password' => static::CORRECT_PASSWORD
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Tests that a login attempt with an incorrect password fails.
     */
    public function testLoginIncorrectPassword()
    {
        $this->createUser();

        $response = $this->post($this->getLoginSubmitUrl(), [
            'email' => static::CORRECT_EMAIL,
            'password' => 'incorrectPassword'
        ]);

        //Regardless of incorrect email/password, email is sent as error key.
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Tests that a login attempt with correct credential succeeds,
     * and redirects the user properly. Also checks for logged in user
     * state.
     */
    public function testLoginSuccess()
    {
        $this->createUser();

        $response = $this->post($this->getLoginSubmitUrl(), [
            'email' => static::CORRECT_EMAIL,
            'password' => static::CORRECT_PASSWORD
        ]);

        $response->assertRedirect($this->getSuccessUrl())
            ->assertSessionHasNoErrors();

        $this->assertNotNull(\Auth::user());
    }

    private function getSuccessUrl(): string {
        return route('home');
    }

    private function getLoginUrl(): string {
        return route('login');
    }

    private function getLoginSubmitUrl(): string {
        return route('login');
    }

    /**
     * Creates a mock user.
     *
     * @param string|null $email - the email address to use. If not specified, uses the default.
     * @param string|null $password - the password to use. If not specified, uses the default.
     * @return User - our newly-created user.
     */
    private function createUser(string $email = null, string $password = null): User {
        $email = $email ?? static::CORRECT_EMAIL;
        $password = $password ?? \Hash::make(static::CORRECT_PASSWORD);

        $user = factory(User::class)->create([
            'email' => $email,
            'password' => $password
        ]);

        return $user;
    }

}