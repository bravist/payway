<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentEventsTableSeeder extends Seeder
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
                'name' => 'internal_request_order',
                'desc' => '请求创建网关支付，网关得到内部支付请求',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'external_request_order',
                'desc' => '请求创建渠道支付，网关向渠道发起支付请求',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'internal_request_refund',
                'desc' => '请求创建网关退款，网关得到内部退款请求',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'external_request_refund',
                'desc' => '请求创建渠道退款，网关向渠道发起退款请求',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'external_wehook',
                'desc' => '支付结果异步通知网关，网关得到渠道异步通知请求',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'internal_webhook',
                'desc' => '支付结果异步通知业务系统，网关向业务系统发起支付结果通知',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'external_query_paid_order',
                'desc' => '定时查询订单渠道支付结果，用户付款成功，网关未得到渠道支付结果通知',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'external_query_expired_order',
                'desc' => '定时查询订单渠道支付结果，用户未付款，超过渠道订单付款时间',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'external_query_refund',
                'desc' => '定时查询退款单结果，微信退款有1~3个工作日处理时间',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'external_query_order',
                'desc' => '查询网关渠道订单状态',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        collect($defaults)->each(function ($default) {
            DB::table('payment_events')->insert($default);
        });
    }
}
