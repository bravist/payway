<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Ry\Model\Payway\Client::class, function (Faker $faker) {
    return [
        'appid' => 'ry'.$faker->unique()->regexify('[0-9][a-z0-9]{15}'),
        'secret' => $faker->unique()->regexify('[a-z0-9]{32}'),
        'name' => $faker->unique()->company(),
        'desc' => $faker->sentence(),
    ];
});
