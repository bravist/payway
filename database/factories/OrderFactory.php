<?php

use Faker\Generator as Faker;

$factory->define(Ry\Model\Payway\Order::class, function (Faker $faker) {
    return [
        'client_id' => Ry\Model\Payway\Client::all()->random()->id,
        'trade_no' => $faker->uuid(),
        'out_trade_no' => $faker->uuid(),
        'payment_channel_id' => Ry\Model\Payway\Channel::all()->random()->id,
        'channel' => $faker->randomDigitNotNull(),
        'payway' => $faker->randomDigitNotNull(),
        'subject' => $faker->word(),
        'amount' => $faker->randomDigitNotNull(),
        'body' => $faker->word(),
        'detail' => $faker->word(),
        'extra' => '{}',
        'buyer' => $faker->word(),
        'seller' => $faker->word(),
        'pay_at' => Carbon\Carbon::now(),
        'paid_at' => Carbon\Carbon::now(),
        'expired_at' => Carbon\Carbon::now(),
        'status' => 0
    ];
});
