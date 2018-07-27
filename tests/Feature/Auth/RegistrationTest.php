<?php


namespace Tests\Feature\Auth;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laracore\Repository\ModelRepository;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;
    use CreatesUsers;

    /**
     * Tests that a registration attempt fails with no
     * credentials specified.
     */
    public function testRegistrationNoCredentials()
    {
        $response = $this->post($this->getRegistrationSubmitUrl());
        $response->assertSessionHasErrors([
            'name',
            'email',
            'password'
        ]);
    }

    /**
     * Tests that a registration attempt fails when
     * an email is already in use.
     */
    public function testRegistrationEmailInUse()
    {
        $this->createUser();

        $response = $this->post($this->getRegistrationSubmitUrl(), [
            'name' => $this->faker()->name(),
            'email' => $this->getCorrectEmail(),
            'password' => $this->getCorrectPassword(),
            'password_confirmation' => $this->getCorrectPassword()
        ]);

        $response->assertSessionHasErrors([
            'email' => 'The email has already been taken.'
        ]);
    }

    /**
     * Tests that a registration attempt fails when
     * the two specified passwords do not match.
     */
    public function testRegistrationPasswordsDoNotMatch()
    {
        $response = $this->post($this->getRegistrationSubmitUrl(), [
            'name' => $this->faker()->name(),
            'email' => $this->getCorrectEmail(),
            'password' => $this->getCorrectPassword(),
            'password_confirmation' => 'notSecret'
        ]);

        $response->assertRedirect('/')
            ->assertSessionHasErrors([
                'password' => 'The password confirmation does not match.'
            ]);
    }

    /**
     * Tests that a registration attempt succeeds and
     * properly logs in a user.
     */
    public function testRegistrationSuccess()
    {
        $name = $this->faker()->name();
        $response = $this->post($this->getRegistrationSubmitUrl(), [
            'name' => $name,
            'email' => $this->getCorrectEmail(),
            'password' => $this->getCorrectPassword(),
            'password_confirmation' => $this->getCorrectPassword()
        ]);

        $this->assertTrue($response->isSuccessful() || $response->isRedirection());

        $repository = app(ModelRepository::class);
        $repository->setModel(User::class);
        /** @var User $user */
        $user = $repository->query()
            ->where('email', '=', $this->getCorrectEmail())
            ->first();

        $this->assertEquals($name, $user->name);
        $this->assertNotNull(\Auth::user());
    }

    private function getRegistrationSubmitUrl(): string {
        return route('register');
    }
}