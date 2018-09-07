<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Log::class, function (Faker $faker) {
    return [
        'payment_event_id' => App\Models\Event::all()->random()->id,
        'logger_id' => App\Models\Order::all()->random()->id,
        'logger_type' => App\Models\Order::all()->random()->getMorphClass(),
        'context' => $faker->sentence(),
    ];
});
