<?php

use App\Models\Link;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Link::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
        'title' => $faker->company,
        'description' => $faker->sentence
    ];
});

$factory->state(Link::class, 'previous', function (Faker $faker) {
    $timestamp = Carbon::now()->subDays(rand(1, 100));

    return [
        'created_at' => $timestamp,
        'updated_at' => $timestamp
    ];
});