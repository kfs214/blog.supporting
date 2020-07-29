<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Frequency;
use Faker\Generator as Faker;

$factory->define(Frequency::class, function (Faker $faker) {
    $url_ids = \App\Url::where('user_id', 1)->pluck('id');

    return [
      'user_id' => 2,
      'url_id' => $faker->randomElement($url_ids),
      'number' => $faker->numberBetween(1,10),
      'unit' => $faker->randomElement(['days', 'weeks', 'months', 'years']),
    ];
});
