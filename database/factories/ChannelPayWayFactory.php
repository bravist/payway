<?php

use Faker\Generator as Faker;

/**
 * INSERT INTO `payment_channel_pay_ways` (`id`, `payment_channel_id`, `way`, `merchant_id`, `app_id`, `app_secret`, `certficate`, `key`, `refund_rate`, `created_at`, `updated_at`)
VALUES
    (1, 1, 'wechat_mini', '1337456301', 'wx56e56f00c013c9ab', 'ok986fhe4vbcnd2sdi7do0op1fjdnvjd', '', '', 0.00, '2018-09-10 18:21:23', '2018-09-10 18:21:23');

 */
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
