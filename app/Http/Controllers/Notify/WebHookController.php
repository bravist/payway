<?php

namespace App\Http\Controllers\Notify;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\ExternalWebhook;
use Illuminate\Support\Facades\Event;
use App\Models\ChannelWebhook;
use App\Models\Webhook;
use App\Jobs\WebhookNotifier;
use Carbon\Carbon;
use App\Models\Refund;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Support\XML;
use EasyWeChat\Kernel\Support;
use Illuminate\Support\Facades\DB;
use App\Models\ChannelPayWay;

class WebHookController extends Controller
{
    public function wechatPaymentNotify($appId)
    {
        $response = Factory::payment($this->paymentConfig($appId))
            ->handlePaidNotify(function ($message, $fail) use ($order) {
                //开启事务
                DB::beginTransaction();
                //记录日志
                Event::fire(new ExternalWebhook($order, [], $message));
                //订单异常检查
                if (! $order
                || $order->status == Order::PAY_STATUS_SUCCESS
                || $order->status == Order::PAY_STATUS_CLOSED) {
                    return true;
                }
                //业务判断
                if ($message['return_code'] == 'SUCCESS'
                && $message['result_code'] == 'SUCCESS'
                ) { // return_code 表示通信状态，不代表支付状态
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
                    DB::commit();
                } else {
                    return $fail('通信失败，请稍后再通知我');
                }
                DB::rollBack();
                return true; // 返回处理完成
            });
        return $response; // return $response;
    }

    /**
     * [paymentConfig description]
     * @param  [type] $appId [description]
     * @return [type]        [description]
     */
    protected function paymentConfig($appId)
    {
        $channelPayWay = ChannelPayWay::where('app_id', $appId)->first();
        if (! $channelPayWay) {
            return [];
        }
        return [
            // 必要配置
            'app_id' => $channelPayWay->app_id,
            'mch_id' => $channelPayWay->merchant_id,
            'key'    => $channelPayWay->app_secret,   // API 密钥
        ];
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
            'expired_at' => $order->expired_at,
            'order_status' => $order->status,
            'refund_status' => $refund ? $refund->status : '',
            'order_channel_webhook' => $order->prepay->response,
            'refund_channel_webhook' => $refund ? $refund->prepay->response : '',
        ];
        return json_encode($context);
    }

    /**
     * [wechatRefundNotify description]
     * @param  [type] $appId [description]
     * @return [type]        [description]
     */
    public function wechatRefundNotify($appId)
    {
        $response = Factory::payment($this->paymentConfig($appId))
            ->handleRefundedNotify(function ($message, $reqInfo, $fail) {
                //开启事务
                DB::beginTransaction();
                //退款单是否存在
                $refund = Refund::where('refund_no', $reqInfo['out_refund_no'])->first();
                //记录日志
                Event::fire(new ExternalWebhook($refund, [], $reqInfo));
                //退款是否异常
                if (! $refund
                || $refund->status == Refund::STATUS_CLOSED
                || $refund->status == Refund::STATUS_SUCCESS) {
                    return true;
                }
                if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                    // 渠道通知
                    ChannelWebhook::create([
                    'client_id' => $refund->order->client_id,
                    'webhookable_id' => $refund->id,
                    'webhookable_type' => $refund->getMorphClass(),
                    'trade_no' => $refund->trade_no,
                    'payment_channel_id' => $refund->order->payment_channel_id,
                    'out_trade_no' => $refund->order->out_trade_no,
                    'channel_trade_no' => $reqInfo['refund_id'],
                    'channel' => $refund->order->channel,
                    'context' => json_encode($reqInfo),
                ]);
                    //网关通知
                    $notifier = Webhook::create([
                    'client_id' => $refund->order->channel->client_id,
                    'trade_no' => $refund->trade_no,
                    'payment_channel_id' => $refund->order->channel->id,
                    'webhookable_id' => $refund->id,
                    'webhookable_type' => $refund->getMorphClass(),
                    'out_trade_no' => $refund->out_trade_no,
                    'channel_trade_no' => $reqInfo['refund_id'],
                    'trade_no' => $refund->trade_no,
                    'url' => $refund->order->channel()->first()->notify_url,
                    'context' => $this->notifyContext($refund->order, $refund)
                ]);
                    //退款成功
                    if ($reqInfo['refund_status'] == 'SUCCESS') {
                        $refund->update([
                        'status' => Refund::STATUS_SUCCESS,
                        'refunded_at' => $message['success_time'],
                    ]);
                        WebhookNotifier::dispatch($notifier)->onQueue('webhook-notifier');
                        DB::commit();
                    }
                } else {
                    return $fail('通信失败，请稍后再通知我');
                }
                DB::rollBack();
                return true; // 返回处理完成
            });
        return $response; // return $response;
    }
}
