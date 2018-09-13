<?php

namespace App\Services;

use App\Services\PaymentService;
use EasyWeChat\Factory;

class WechatMwebService extends PaymentService
{
    /**
    * Pay wechat mini_program
    * @param  Order  $order   [description]
    * @param  array  &$params [description]
    * @return [type]          [description]
    */
    public function pay(&$params = [])
    {
        $config = [
            // 必要配置
            'app_id'             => $this->channelPayWay->app_id,
            'mch_id'             => $this->channelPayWay->merchant_id,
            'key'                => $this->channelPayWay->app_secret,   // API 密钥
        ];
        $app = Factory::payment($config);
        $params = [
            'body' => $this->order->body,
            'out_trade_no' => $this->order->trade_no,
            'total_fee' => $this->order->amount,
            'trade_type' => 'MWEB',
            'notify_url' => $this->channel->notify_url,
            'time_start' => $this->order->pay_at->format('YmdHis'),
            'time_expire' => $this->order->expired_at->format('YmdHis')
        ];
        return $app->order->unify($params);
    }
}
