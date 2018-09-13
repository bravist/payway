<?php

namespace App\Services;

use App\Services\PaymentService;
use EasyWeChat\Factory;

class WechatMiniService extends PaymentService
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
            'trade_type' => 'JSAPI',
            'notify_url' => $this->channel->notify_url
        ];
        return $app->order->unify($params);
    }
}
