<?php


namespace App\Models\Factory;


use App\Models\User;
use Laracore\Factory\ModelFactory;
use Laracore\Repository\ModelRepository;

class UserFactory extends ModelFactory
{

    /**
     * {@inheritdoc}
     */
    public function instantiateRepository()
    {
        return new ModelRepository(User::class);
    }


    /**
     * Makes a new user.
     *
     * @param $name
     * @param $email
     * @param $password
     * @return User
     */
    public function makeNewUser($name, $email, $password)
    {
        return $this->make([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }

}