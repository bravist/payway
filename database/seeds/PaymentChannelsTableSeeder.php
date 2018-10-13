<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentChannelsTableSeeder extends Seeder
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
                'client_id' => '1',
                'channel' => 'wechat',
                'desc' => '若愚优选微信支付',
                'return_url' => '',
                'notify_url' => 'https://mall-dev.ruoyubuy.com/api/inner/payway/callback',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'client_id' => '2',
                'channel' => 'wechat',
                'desc' => '若愚精选微信支付',
                'return_url' => '',
                'notify_url' => 'https://mall-dev.ruoyubuy.com/api/inner/payway/callback',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        collect($defaults)->each(function ($default) {
            DB::table('channels')->insert($default);
        });
    }
}
