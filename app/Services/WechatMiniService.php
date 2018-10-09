<?php

namespace App\Services;

use App\Services\WechatService;

class WechatMiniService extends WechatService
{
    /**
    * Pay wechat mini_program
    * @param  Order  $order   [description]
    * @param  array  &$params [description]
    * @return [type]          [description]
    */
    public function pay(&$params = [])
    {
        $params = [
            'body' => $this->order->body ? $this->order->body : $this->order->subject,
            'out_trade_no' => $this->order->trade_no,
            'total_fee' => $this->order->amount,
            'trade_type' => 'JSAPI',
            'openid' => $this->order->buyer,
            'notify_url' => config('wechat.payment.default.notify_url') . '/' . $this->channelPayWay->app_id,
        ];
        return $this->getApp()->order->unify($params);
    }
}
