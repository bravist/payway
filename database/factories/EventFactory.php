<?php

use Faker\Generator as Faker;

$factory->define(Ry\Model\Payway\Event::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'desc' => $faker->name(),
    ];
});
