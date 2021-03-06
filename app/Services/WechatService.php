<?php

namespace App\Services;

use App\Services\PaymentService;
use EasyWeChat\Factory;

class WechatService extends PaymentService
{
    public function setConfig()
    {
        $certPath = $this->channelPayWay->wx_certs ? storage_path('wechat/' . json_decode($this->channelPayWay->wx_certs)->cert_path) : '';
        $keyPath = $this->channelPayWay->wx_certs ? storage_path('wechat/' . json_decode($this->channelPayWay->wx_certs)->key_path): '';
        return [
            // 必要配置
            'app_id'             => $this->channelPayWay->app_id,
            'mch_id'             => $this->channelPayWay->merchant_id,
            'key'                => $this->channelPayWay->app_secret,   // API 密钥
            'cert_path'         => $certPath,
            'key_path'         => $keyPath,
        ];
    }

    public function getApp()
    {
        return Factory::payment($this->setConfig());
    }

    public function pay(&$params = [])
    {
    }

    public function refund()
    {
        return $this->getApp()->refund->byOutTradeNumber(
            $this->order->trade_no,
            $this->refund->refund_no,
            $this->order->amount,
            $this->refund->amount,
            [
                // 可在此处传入其他参数，详细参数见微信支付文档
                'refund_desc' => $this->refund->reason,
                'notify_url' => config('wechat.payment.refund.notify_url') . '/' . $this->channelPayWay->app_id,
            ]
        );
    }

    public function queryRefund()
    {
        return $this->getApp()->refund->queryByOutRefundNumber($this->refund->refund_no);
    }
}
