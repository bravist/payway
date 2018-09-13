<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const CHANNEL_WECHAT = 'wechat';
    const CHANNEL_ALIPAY = 'alipay';

    const CHANNEL_PAY_WAY_WECHAT_MINI = 'wechat_mini';
    const CHANNEL_PAY_WAY_WECHAT_JSAPI = 'wechat_jsapi';
    const CHANNEL_PAY_WAY_WECHAT_NATIVE = 'wechat_native';
    const CHANNEL_PAY_WAY_WECHAT_MWEB = 'wechat_mweb';

    const PAY_STATUS_PENDING = 'pending';
    const PAY_STATUS_PROCESSING = 'processing';
    const PAY_STATUS_SUCCESS = 'success';
    const PAY_STATUS_CLOSED = 'closed';
    const PAY_STATUS_CANCELED = 'canceled';

    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS = 'success';
    const REFUND_STATUS_CLOSED = 'closed';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_orders';

    protected $appends = ['prepay'];

    protected $casts = [
        'paid_at' => 'datetime:Y-m-d H:i:s',
        'expired_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
    * Order has own Channel
    * @return App\Models\Channel
    */
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'payment_channel_id');
    }

    /**
     * Polymorphic Relations
     * @return [type] [description]
     */
    public function logs()
    {
        return $this->morphMany(Log::class, 'logger');
    }

    /**
     * Channel pay way
     * @return [type] [description]
     */
    public function channelPayWay()
    {
        return $this->belongsTo(ChannelPayWay::class, 'payment_channel_pay_way_id');
    }

    /**
     * Many To Many Polymorphic Relations
     * @return [type] [description]
     */
    public function events()
    {
        return $this->morphToMany(Event::class, 'logger', 'payment_logs', null, 'payment_event_id');
    }

    /**
     * Prepay
     * @return [type] [description]
     */
    public function prepay()
    {
        $event = Event::where('name', Event::EXTERNAL_REQUEST_ORDER)->first();
        return $this->logs()->where('payment_event_id', $event->id)->first();
    }

    public function getPrepayAttribute()
    {
        return $this->prepay();
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class, 'payment_order_id');
    }

    public function successfulRefund()
    {
        return $this->refunds()->where('status', Refund::STATUS_SUCCESS)->first();
    }

    public function processingRefund()
    {
        return $this->refunds()->where('status', Refund::STATUS_PROCESSING)->first();
    }
}
