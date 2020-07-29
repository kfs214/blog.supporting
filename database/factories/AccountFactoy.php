<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
      'user_id' => $faker->numberBetween(1,2),
      'type' => $faker->randomElement(['facebook', 'twitter', 'email']),
      'account' => $faker->userName,
    ];
});
