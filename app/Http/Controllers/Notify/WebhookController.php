<?php

namespace App\Http\Controllers\Notify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\ExternalWebhook;
use Illuminate\Support\Facades\Event;
use App\Models\ChannelWebhook;
use App\Models\Webhook;
use App\Jobs\WebhookNotifier;

class WebhookController extends Controller
{
    public function wechatNotify()
    {
        $app = Factory::payment();
        $response = $app->handlePaidNotify(function($message, $fail){
            $order = Order::where('trade_no', $message['out_trade_no'])->first();
            Event::fire(new ExternalWebhook($order, [], $message));
            if (! $order 
                || $order->status == Order::PAY_STATUS_SUCCESS
                || $order->status == Order::PAY_STATUS_CLOSED) {
                return true;
            }
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                ChannelWebhook::create([
                    'client_id' => $order->channel->client_id,
                    'payment_order_id' => $order->id,
                    'trade_no' => $order->trade_no,
                    'payment_channel_id' => $order->channel->id,
                    'out_trade_no' => $order->out_trade_no,
                    'channel_trade_no' => $message['transaction_id'],
                    'channel' => $order->$order->channel,
                    'context' => json_encode($message),
                ]);
                $notifier = Webhook::create([
                    'client_id' => $order->channel->client_id,
                    'trade_no' => $order->trade_no,
                    'payment_channel_id' => $order->channel->id,
                    'payment_order_id' => $order->id,
                    'out_trade_no' => $order->out_trade_no,
                    'channel_trade_no' => $message['transaction_id'],
                    'trade_no' => $order->trade_no,
                    'url' => $order->channel->notify_url,
                    'context' => json_encode($order->toArray()),
                    'channel_context' => $message
                ]);
                $order->update([
                    'status' => Order::PAY_STATUS_SUCCESS
                ]);
                WebhookNotifier::dispatch($notifier)->onQueue('webhook-notifier');
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
            return true; // 返回处理完成
        });
        return $response; // return $response;
    }

}
