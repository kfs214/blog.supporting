<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Url;
use Faker\Generator as Faker;

$factory->define(Url::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1,2),
        'url' => $faker->url,
    ];
});
