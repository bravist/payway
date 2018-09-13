<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Channel;
use App\Models\ChannelPayWay;
use App\Models\Refund;

abstract class PaymentService
{
    public $order;

    public $channel;

    public $channelPayWay;

    public $refund;

    /**
     * [__construct description]
     * @param Order         $order         [description]
     * @param Channel       $channel       [description]
     * @param ChannelPayWay $channelPayWay [description]
     */
    public function __construct(Order $order, Channel $channel, ChannelPayWay $channelPayWay, Refund $refund = null)
    {
        $this->order = $order;
        $this->channel = $channel;
        $this->channelPayWay = $channelPayWay;
        $this->refund = $refund;
    }

    abstract public function pay(&$params);
}
