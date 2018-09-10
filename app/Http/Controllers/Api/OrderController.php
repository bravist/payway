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

class OrderController extends Controller
{
    /**
     * Create Order
     * @param  OrderRequest $request [description]
     * @return [type]                [description]
     */
    public function store(OrderRequest $request)
    {
        //生成订单
        try {
            DB::beginTransaction();
            $order = $this->createOrder($request);

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

}
