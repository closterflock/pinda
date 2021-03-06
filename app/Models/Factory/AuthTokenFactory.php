<?php


namespace App\Models\Factory;


use App\Models\AuthToken;
use App\Models\User;
use Laracore\Factory\ModelFactory;
use Laracore\Repository\ModelRepository;

class AuthTokenFactory extends ModelFactory
{
    /**
     * {@inheritdoc}
     */
    public function instantiateRepository()
    {
        return new ModelRepository(AuthToken::class);
    }

    /**
     * Makes a new token for this user.
     *
     * @param User $user
     * @param $ipAddress
     * @param $userAgent
     * @return AuthToken
     */
    public function makeNewToken(User $user, $ipAddress, $userAgent)
    {
        return $this->make([
            'token' => $this->generateUniqueToken(),
            'ip' => $ipAddress,
            'user_agent' => $userAgent
        ], [
            'user' => $user
        ]);
    }

    /**
     * Generates a unique API token.
     *
     * @return string
     */
    public function generateUniqueToken()
    {
        do {
            $token = str_random(32);
        } while ($this->getRepository()->query()->where('token', '=', $token)->first() instanceof AuthToken);

        return $token;
    }

}