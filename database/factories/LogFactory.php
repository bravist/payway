<?php

use Faker\Generator as Faker;

$factory->define(Ry\Model\Payway\Log::class, function (Faker $faker) {
    return [
        'payment_event_id' => Ry\Model\Payway\Event::all()->random()->id,
        'logger_id' => Ry\Model\Payway\Order::all()->random()->id,
        'logger_type' => Ry\Model\Payway\Order::all()->random()->getMorphClass(),
        'context' => $faker->sentence(),
    ];
});
