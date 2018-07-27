<?php

use App\Models\Factory\AuthTokenFactory;
use Faker\Generator as Faker;

$factory->define(App\Models\AuthToken::class, function (Faker $faker) {
    /** @var AuthTokenFactory $authTokenFactory */
    $authTokenFactory = app(AuthTokenFactory::class);
    return [
        'ip' => $faker->ipv4,
        'user_agent' => $faker->userAgent,
        'token' => $authTokenFactory->generateUniqueToken()
    ];
});
