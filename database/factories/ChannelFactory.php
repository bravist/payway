<?php

use Faker\Generator as Faker;


$factory->define(App\Models\Channel::class, function (Faker $faker) {
    return [
        'client_id' => Laravel\Passport\Client::all()->random()->id,
        'channel' => $faker->randomDigitNotNull(),
        'desc' => $faker->word(),
        'return_url' => $faker->url(),
        'notify_url' => $faker->url(),
    ];
});