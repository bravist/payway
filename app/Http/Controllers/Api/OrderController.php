<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Channel;

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
        //创建订单基本信息
        $order = Order::create([
            'out_trade_no' => $request->out_trade_no,
            'client_id' => $token->client_id,
            'payment_channel_id' => $channel->id,
            'channel' => $channel->channel,
            'payway' => $request->out_trade_no,
            'out_trade_no' => $request->out_trade_no,
            'out_trade_no' => $request->out_trade_no,
        ]);
        //订单号生成并回写
        $order->update();
    }

}
