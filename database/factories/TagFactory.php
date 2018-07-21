<?php

use App\Models\Tag;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->state(Tag::class, 'previous', function (Faker $faker) {
    $timestamp = Carbon::now()->subDays(rand(1, 100));

    return [
        'created_at' => $timestamp,
        'updated_at' => $timestamp
    ];
});
