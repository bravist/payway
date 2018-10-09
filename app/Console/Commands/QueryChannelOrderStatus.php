<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\ExternalQueryOrder;
use EasyWeChat\Factory;
use App\Models\Order;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;
use App\Models\ChannelWebhook;
use App\Models\Webhook;
use App\Jobs\WebhookNotifier;

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
        logger($res);
        if ($res['return_code'] == 'SUCCESS') {
            if ($res['trade_state'] == self::WECHAT_TRADE_STATUS_SUCCESS) {
                ChannelWebhook::create([
                    'client_id' => $order->client_id,
                    'webhookable_id' => $order->id,
                    'webhookable_type' => $order->getMorphClass(),
                    'trade_no' => $order->trade_no,
                    'payment_channel_id' => $order->payment_channel_id,
                    'out_trade_no' => $order->out_trade_no,
                    'channel_trade_no' => $message['transaction_id'],
                    'channel' => $order->channel,
                    'context' => json_encode($message),
                ]);
                $order->update([
                    'status' => Order::PAY_STATUS_SUCCESS,
                    'paid_at' => Carbon::now()
                ]);
                $order = $order->fresh();
                $notifier = Webhook::create([
                    'client_id' => $order->client_id,
                    'trade_no' => $order->trade_no,
                    'payment_channel_id' => $order->payment_channel_id,
                    'webhookable_id' => $order->id,
                    'webhookable_type' => $order->getMorphClass(),
                    'out_trade_no' => $order->out_trade_no,
                    'channel_trade_no' => $message['transaction_id'],
                    'trade_no' => $order->trade_no,
                    'url' => $order->channel()->first()->notify_url,
                    'context' => $this->notifyContext($order)
                ]);
                WebhookNotifier::dispatch($notifier)->onQueue('webhook-notifier');
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

    /**
     * [notifyContext description]
     * @param  [type] $order  [description]
     * @param  [type] $refund [description]
     * @return [type]         [description]
     */
        protected function notifyContext($order, $refund = null)
        {
            $context = [
                'type' => $refund ? 'refund' : 'order',
                'trade_no' => $order->trade_no,
                'out_trade_no' => $order->out_trade_no,
                'channel' => $order->channel,
                'pay_way' => $order->pay_way,
                'subject' => $order->subject,
                'amount' => $order->amount,
                'body' => $order->body,
                'detail' => $order->detail,
                'extra' => $order->extra,
                'buyer' => $order->buyer,
                'seller' => $order->seller,
                'pay_at' => $order->pay_at,
                'paid_at' => $order->paid_at,
                'refunded_at' => $refund ? $refund->refunded_at : '',
                'expired_at' => $order->expired_at,
                'order_status' => $order->status,
                'refund_status' => $refund ? $refund->status : '',
                'order_channel_webhook' => $order->prepay->response,
                'refund_channel_webhook' => $refund->prepay ? $refund->prepay->response : '',
            ];
            return json_encode($context);
        }
}
