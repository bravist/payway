<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Channel;
use App\Models\ChannelPayWay;
use Carbon\Carbon;
use App\Events\InternalRequestOrder;
use App\Events\ExternalRequestOrder;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Event;


class OrderController extends Controller
{
    /**
     * Create Order
     * @param  OrderRequest $request [description]
     * @return [type]                [description]
     */
    public function store(OrderRequest $request)
    {
        $params = [];
        //接收创建订单参数
        //验证签名
        try {
            DB::beginTransaction();
            //生成新订单
            $order = $this->createOrder($request);
            //创建生成新订单请求日志
            Event::fire(new InternalRequestOrder($request, $order));
            //创建渠道订单请求
            switch ($request->pay_way) {
                case Order::CHANNEL_PAY_WAY_WECHAT_MINI:
                        $response = $this->payWechatMini($order, $params);
                        //创建生成小程序支付请求日志
                        Event::fire(new ExternalRequestOrder($order, $request, $response));
                    break;
            }
            //更新订单状态
            $order->update([
                'status' => Order::
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        //创建订单请求日志（业务系统请求网关）监听器
    }

    /**
     * Create order
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    protected function createOrder($request)
    {   
        $token = $this->retrieveTokenByRequest($request);
        $channel = Channel::where('client_id', $token->client_id)->first();
        $payWay = ChannelPayWay::where('payment_channel_id', $channel->id)->where('way', $request->pay_way)->first();

        //创建订单基本信息
        $order = Order::create([
            'out_trade_no' => $request->out_trade_no,
            'client_id' => $token->client_id,
            'payment_channel_id' => $channel->id,
            'channel' => $channel->channel,
            'payment_channel_pay_way_id' => $payWay->id,
            'pay_way' => $request->pay_way,
            'subject' => $request->subject,
            'amount' => intval($request->amount),
            'body' => $request->body,
            'detail' => $request->detail,
            'extra' => $request->extra,
            'buyer' => $request->buyer,
            'seller' => $request->has('seller') ? $request->seller : $payWay->merchant_id,
            'pay_at' => Carbon::now(),
            'expired_at' => Carbon::now()->addHour(2)
        ]);
        //订单号生成并回写
        $orderNo = sprintf(
            '%s%s', 
            Carbon::now()->timezone('Asia/Shanghai')->format('YmdHis'), 
            str_pad($order->id, 4, 0, STR_PAD_LEFT)
        );
        $order->update([
            'trade_no' => $orderNo
        ]);
        return $order;
    }

    /**
     * Pay wechat mini_program
     * @param  Order  $order   [description]
     * @param  array  &$params [description]
     * @return [type]          [description]
     */
    public function payWechatMini(Order $order, &$params = [])
    {
        $config = [
            // 必要配置
            'app_id'             => config('wechat.payment.default.app_id'),
            'mch_id'             => config('wechat.payment.default.mch_id'),
            'key'                => config('wechat.payment.default.key'),   // API 密钥
        ];
        $app = Factory::payment($config);
        $params = [
            'body' => $order->body,
            'out_trade_no' => $order->trade_no,
            'total_fee' => $order->amount,
            'trade_type' => 'JSAPI',
            'notify_url' => config('wechat.payment.default.notify_url')
        ];
        return $app->order->unify($params);
    }
}
