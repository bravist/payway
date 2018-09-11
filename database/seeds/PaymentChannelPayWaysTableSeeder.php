<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class PaymentChannelPayWaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaults = [
            [
                'payment_channel_id' => '1',
                'way' => 'wechat_mweb',
                'merchant_id' => '1337456301',
                'app_id' => 'wx56e56f00c013c9ab',
                'app_secret' => 'ok986fhe4vbcnd2sdi7do0op1fjdnvjd',
                'certficate' => '',
                'key' => '',
                'refund_rate' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        collect($defaults)->each(function ($default) {
            DB::table('payment_channel_pay_ways')->insert($default);
        });
    }
}
