<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ChannelPayWay::class, function (Faker $faker) {
    return [
        'payment_channel_id' => App\Models\Channel::all()->random()->id,
        'way' => $faker->randomDigitNotNull(),
        'merchant_id' => $faker->randomNumber(),
        'app_id' => $faker->randomNumber(),
        'app_secret' => $faker->randomNumber(),
        'certficate' => $faker->randomNumber(),
        'key' => $faker->word(),
        'refund_rate' => $faker->randomFloat(null, 0, 10),
    ];
});
