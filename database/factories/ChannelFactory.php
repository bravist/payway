<?php

use Faker\Generator as Faker;

/**
 * INSERT INTO `payment_channels` (`id`, `client_id`, `channel`, `desc`, `return_url`, `notify_url`, `created_at`, `updated_at`, `deleted_at`)
VALUES
    (1, 1, 'wechat', '微信支付', '', '', '2018-09-10 18:21:22', '2018-09-10 18:21:22', NULL);

 */
$factory->define(App\Models\Channel::class, function (Faker $faker) {
    return [
        'client_id' => Laravel\Passport\Client::all()->random()->id,
        'channel' => $faker->randomDigitNotNull(),
        'desc' => $faker->word(),
        'return_url' => $faker->url(),
        'notify_url' => $faker->url(),
    ];
});
