<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Refund;
use App\Events\ExternalQueryRefund;
use EasyWeChat\Factory;
use App\Models\Order;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;

class QueryChannelRefundStatus extends Command
{
    const WECHAT_REFUND_STATUS_SUCCESS = 'SUCCESS';
    const WECHAT_REFUND_STATUS_REFUND = 'CHANGE';
    const WECHAT_REFUND_STATUS_NOTPAY = 'REFUNDCLOSE';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:query-channel-refund-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Timely query channel refund status';

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
        Refund::whereIn('status', [Refund::STATUS_PENDING, STATUS_PROCESSING])
            ->get()
            ->each(function ($refund) {
                if ($refund->order->channel == Order::CHANNEL_WECHAT) {
                    $this->queryWechatRefundStatus($order);
                }
            });
    }

    /**
     * Query wechat order status
     * @param  Order  $order [description]
     * @return [type]        [description]
     */
    protected function queryWechatRefundStatus(Refund $refund)
    {
        $config = [
            // 必要配置
            'app_id' => $order->channelPayWay->app_id,
            'mch_id' => $order->channelPayWay->merchant_id,
            'key' => $order->channelPayWay->app_secret,   // API 密钥
        ];
        $app = Factory::payment($config);
        $res = $app->order->queryByOutRefundNumber($refund->refund_no);
        Event::fire(new ExternalQueryRefund($refund, [$refund->refund_no], $res));
        if ($res['return_code'] == 'SUCCESS') {
            //关闭订单
            $refund->update([
                'status' => Refund::STATUS_SUCCESS,
                'refunded_at' => $res['success_time'],
            ]);
        }
    }
}
