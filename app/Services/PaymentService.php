<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Channel;
use App\Models\ChannelPayWay;

abstract class PaymentService
{
    public $order;

    public $channel;

    public $channelPayWay;

    /**
     * [__construct description]
     * @param Order         $order         [description]
     * @param Channel       $channel       [description]
     * @param ChannelPayWay $channelPayWay [description]
     */
    public function __construct(Order $order, Channel $channel, ChannelPayWay $channelPayWay)
    {
        $this->order = $order;
        $this->channel = $channel;
        $this->channelPayWay = $channelPayWay;
    }

    abstract public function pay(&$params);
}
