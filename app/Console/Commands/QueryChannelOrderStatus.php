<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\ExternalQueryOrder;
use EasyWeChat\Factory;
use App\Models\Order;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;

class QueryChannelOrderStatus extends Command
{
    const WECHAT_TRADE_STATUS_SUCCESS = 'SUCCESS';
    const WECHAT_TRADE_STATUS_REFUND = 'REFUND';
    const WECHAT_TRADE_STATUS_NOTPAY = 'NOTPAY';
    const WECHAT_TRADE_STATUS_CLOSED = 'CLOSED';
    const WECHAT_TRADE_STATUS_REVOKED = 'REVOKED';
    const WECHAT_TRADE_STATUS_USERPAYING = 'USERPAYING';
    const WECHAT_TRADE_STATUS_PAYERROR = 'PAYERROR';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:query-channel-order-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Timely query channel order status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Order::where('status', Order::PAY_STATUS_PROCESSING)
            ->get()
            ->each(function ($order) {
                if ($order->channel == Order::CHANNEL_WECHAT) {
                    if (Carbon::now()->gte($order->expired_at)) {
                        $this->queryWechatOrderStatus($order);
                    }
                }
            });
    }

    /**
     * Query wechat order status
     * @param  Order  $order [description]
     * @return [type]        [description]
     */
    protected function queryWechatOrderStatus(Order $order)
    {
        $config = [
            // 必要配置
            'app_id' => $order->channelPayWay->app_id,
            'mch_id' => $order->channelPayWay->merchant_id,
            'key' => $order->channelPayWay->app_secret,   // API 密钥
        ];
        $app = Factory::payment($config);
        $res = $app->order->queryByOutTradeNumber($order->trade_no);
        if ($res['return_code'] == 'SUCCESS') {
            if ($res['trade_state'] == self::WECHAT_TRADE_STATUS_SUCCESS) {
                //TODO
                Event::fire(new ExternalQueryOrder($order, ExternalQueryOrder::PAY_STATUS_PAID, [$order->trade_no], $res));
            } else {
                //关闭订单
                $order->update([
                    'status' => Order::PAY_STATUS_CLOSED
                ]);
                Event::fire(new ExternalQueryOrder($order, ExternalQueryOrder::PAY_STATUS_EXPIRED, [$order->trade_no], $res));
            }
        } else {
            Event::fire(new ExternalQueryOrder($order, ExternalQueryOrder::PAY_STATUS_QUERY, [$order->trade_no], $res));
        }
    }
}
