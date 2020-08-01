<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Email;
use Faker\Generator as Faker;

$factory->define(Email::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(2,3),
        'group' => $faker->randomElement(['a', 'b']),
        'email' => $faker->email,
    ];
});
