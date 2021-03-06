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
                'refund_rate' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'payment_channel_id' => '1',
                'way' => 'wechat_mini',
                'merchant_id' => '1515108091',
                'app_id' => 'wx36ba7b235f88146e',
                'app_secret' => 'Sichuandazhiruoyudianzishangwu88',
                'wx_certs' => '{"cert_path":"ruoyu_youxuan_apiclient_cert_.pem","key_path":"ruoyu_youxuan_apiclient_key.pem"}',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'payment_channel_id' => '2',
                'way' => 'wechat_mini',
                'merchant_id' => '1516336841',
                'app_id' => 'wxa4c8050a9dea59c3',
                'app_secret' => 'Ruoyujingxuanxcx2018101588886666',
                'wx_certs' => '{"cert_path":"ruoyu_jingxuan_apiclient_cert.pem","key_path":"ruoyu_jingxuan_apiclient_key.pem"}',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        collect($defaults)->each(function ($default) {
            DB::table('channel_pay_ways')->insert($default);
        });
    }
}
