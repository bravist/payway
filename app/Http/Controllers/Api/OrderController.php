<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRequest;
use Ry\Model\Payway\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ry\Model\Payway\Channel;
use Ry\Model\Payway\ChannelPayWay;
use Carbon\Carbon;
use App\Events\InternalRequestOrder;
use App\Events\InternalRequestRefund;
use App\Events\ExternalRequestOrder;
use App\Events\ExternalRequestRefund;
use Illuminate\Support\Facades\Event;
use App\Services\WechatMwebService;
use App\Services\WechatMiniService;
use App\Services\WechatService;
use App\Http\Resources\OrderResource;
use Ry\Model\Payway\Refund;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderController extends Controller
{
    protected function getChannel($clientId)
    {
        return Channel::where('client_id', $clientId)->first();
    }

    protected function getPayWay($way, $channelId)
    {
        return ChannelPayWay::where('payment_channel_id', $channelId)
            ->where('way', $way)
            ->first();
    }

    /**
     * Create Order
     * @param  OrderRequest $request [description]
     * @return [type]                [description]
     */
    public function store(OrderRequest $request)
    {
        $params = $response = [];
        //接收创建订单参数
        //验证签名
        DB::beginTransaction();
        $channel = $this->getChannel($this->client()->id);
        $payWay = $this->getPayWay($request->pay_way, $channel->id);
        try {
            $order = Order::where('out_trade_no', $request->out_trade_no)
                            ->whereNotIn('status', [Order::PAY_STATUS_CLOSED, Order::PAY_STATUS_CANCELED])
                            ->where('payment_channel_id', $channel->id)
                            ->where('payment_channel_pay_way_id', $payWay->id)
                            ->orderBy('created_at', 'desc')
                            ->first();
            if ($order) {
                //订单是否支的付成功
                if ($order->status == Order::PAY_STATUS_SUCCESS) {
                    throw new HttpException(400, '订单已经支付成功');
                }
                //订单归属人
                if ($this->client()->id != $order->client_id) {
                    throw new HttpException(403, '没有权限操作该订单');
                }
                //订单是否过期
                if (Carbon::now()->gte($order->expired_at)) {
                    $order = $this->createOrder($request, $channel, $payWay);
                } else {
                    //返回预付单信息
                    return new OrderResource($order);
                }
            } else {
                //生成新订单
                $order = $this->createOrder($request, $channel, $payWay);
            }
            //创建生成新订单请求日志
            Event::fire(new InternalRequestOrder($request, $order));
            //创建渠道订单请求
            switch ($request->pay_way) {
                case Order::CHANNEL_PAY_WAY_WECHAT_MWEB:
                    $payment = new WechatMwebService($order, $channel, $payWay);
                    $response = $payment->pay($params);
                    break;
                case Order::CHANNEL_PAY_WAY_WECHAT_MINI:
                    $payment = new WechatMiniService($order, $channel, $payWay);
                    $response = $payment->pay($params);
                    break;
            }
            if ($response['return_code'] == 'FAIL') {
                throw new HttpException(400, $response['return_msg']);
            }
            //创建生成小程序支付请求日志
            Event::fire(new ExternalRequestOrder($order, $params, $response));
            //更新订单状态
            $order->update([
                'status' => Order::PAY_STATUS_PROCESSING
            ]);
            DB::commit();
            return new OrderResource($order);
        } catch (HttpException $e) {
            Log::warning(sprintf('创建订单失败 message:%s', $e->getMessage()));
            DB::rollBack();
            abort($e->getStatusCode(), $e->getMessage());
        }
        //创建订单请求日志（业务系统请求网关）监听器
    }

    /**
     * Create order
     * @param  Request $request [description]
     * @return [type]          [description]
     */
    protected function createOrder($request, $channel, $payWay)
    {
        //创建订单基本信息
        $order = Order::create([
            'out_trade_no' => $request->out_trade_no,
            'client_id' => $channel->client_id,
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
     * Refund
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function refund(Request $request)
    {
        //创建退款单号, 开启事务
        DB::beginTransaction();
        //提交订单号
        $order = Order::where('out_trade_no', $request->out_trade_no)
            ->where('status', Order::PAY_STATUS_SUCCESS)
            ->first();
        if (!$order) {
            throw new HttpException(404, '没有该订单');
        }
        //订单归属人
        if ($this->client()->id != $order->client_id) {
            throw new HttpException(403, '没有权限操作该订单');
        }
        //是否已经退款完成
        if ($order->successfulRefund()) {
            throw new HttpException(404, '订单已经退款完成');
        }
        $channel = $this->getChannel($order->client_id);
        $payWay = $this->getPayWay($order->pay_way, $order->payment_channel_id);
        if ($order->processingRefund()) {
            throw new HttpException(404, '订单正在退款中');
        }
        try {
            $refund = $this->createRefund($order);
            //创建退款请求日志
            Event::fire(new InternalRequestRefund($refund, $request, []));
            //创建渠道订单请求
            switch ($order->channel) {
                case Order::CHANNEL_WECHAT:
                    $ref = new WechatService($order, $channel, $payWay, $refund);
                    $response = $ref->refund();
                    break;
            }
            if ($response['result_code'] == 'FAIL') {
                throw new HttpException(400, $response['err_code_des']);
            }
            $refund->update([
                'status' => Refund::STATUS_PROCESSING
            ]);
            Event::fire(new ExternalRequestRefund($refund, $request, $response));
            DB::commit();
            return response()->json(['data' => [
                "trade_no" => $order->trade_no,
                'refund_no' => $refund->refund_no
            ]]);
        } catch (HttpException $e) {
            Log::warning(sprintf('创建退款单失败 message:%s', $e->getMessage()));
            DB::rollBack();
            abort($e->getStatusCode(), $e->getMessage());
        }
    }

    /**
     * Crearte refund
     * @param  Order  $order [description]
     * @return [type]        [description]
     */
    protected function createRefund(Order $order)
    {
        $refund = Refund::create([
            'client_id' => $order->client_id,
            'payment_channel_id' => $order->payment_channel_id,
            'payment_order_id' => $order->id,
            'trade_no' => $order->trade_no,
            'amount' => $order->amount,
            'reason' => '用户退货',
        ]);
        //订单号生成并回写
        $refundNo = sprintf(
            '%s%s',
            Carbon::now()->timezone('Asia/Shanghai')->format('YmdHis'),
            str_pad($order->id, 4, 0, STR_PAD_LEFT)
        );
        $refund->update([
            'refund_no' => $refundNo
        ]);
        return $refund;
    }
}
