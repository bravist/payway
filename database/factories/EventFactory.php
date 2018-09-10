<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Event::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'desc' => $faker->name(),
    ];
});
