<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Order;
use EasyWeChat\Kernel\Support;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $wechatMini = [];
        $prepay = json_decode($this->prepay->response);
        $params = [
            'appId' => $this->channelPayWay->app_id,
            'timeStamp' => time(),
            'nonceStr' => Support\Str::quickRandom(20),
            'package' => 'prepay_id=' . $prepay->prepay_id,
            'prepay_id' => $prepay->prepay_id,
            'signType' => 'MD5',
        ];
        switch ($this->pay_way) {
            case Order::CHANNEL_PAY_WAY_WECHAT_MINI:
                $params['sign'] = Support\generate_sign($params, $this->channelPayWay->app_secret);
                $wechatMini = $params;
                break;
        }
        return [
            'trade_no' => $this->trade_no,
            'wechat_mini' => $wechatMini,
        ];
    }
}
