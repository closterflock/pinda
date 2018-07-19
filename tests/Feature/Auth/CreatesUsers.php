<?php


namespace Tests\Feature\Auth;


use App\Models\User;

trait CreatesUsers
{
    /**
     * Creates a mock user.
     *
     * @param string|null $email - the email address to use. If not specified, uses the default.
     * @param string|null $password - the password to use. If not specified, uses the default.
     * @return User - our newly-created user.
     */
    private function createUser(string $email = null, string $password = null): User {
        $email = $email ?? $this->getCorrectEmail();
        $password = $password ?? $this->getCorrectPassword();
        $hashedPassword = \Hash::make($password);

        $user = factory(User::class)->create([
            'email' => $email,
            'password' => $hashedPassword
        ]);

        return $user;
    }

    protected function getCorrectEmail(): string {
        return 'test@pinda.jamesspencemilwaukee.com';
    }

    protected function getCorrectPassword(): string
    {
        return 'secret';
    }
}