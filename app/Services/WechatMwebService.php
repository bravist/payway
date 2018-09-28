<?php

namespace App\Services;

use App\Services\WechatService;

class WechatMwebService extends WechatService
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
            'body' => $this->order->body,
            'out_trade_no' => $this->order->trade_no,
            'total_fee' => $this->order->amount,
            'trade_type' => 'MWEB',
            'notify_url' => config('wechat.payment.default.notify_url'),
            'time_start' => $this->order->pay_at->format('YmdHis'),
            'time_expire' => $this->order->expired_at->format('YmdHis')
        ];
        return $this->getApp()->order->unify($params);
    }
}
