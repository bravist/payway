<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const CHANNEL_WECHAT = 'wechant';
    const CHANNEL_ALIPAY = 'alipay';

    const CHANNEL_PAY_WAY_WECHAT_MINI = 'wechat_mini';
    const CHANNEL_PAY_WAY_WECHAT_JSAPI = 'wechat_jsapi';
    const CHANNEL_PAY_WAY_WECHAT_NATIVE = 'wechat_native';
    const CHANNEL_PAY_WAY_WECHAT_MWEB = 'wechat_mweb';

    const PAY_STATUS_PENDING = 'pending';
    const PAY_STATUS_PROCESSING = 'processing';
    const PAY_STATUS_SUCCESS = 'success';
    const PAY_STATUS_CLOSED = 'closed';
    const PAY_STATUS_CANELED = 'caneled';

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
     * Many To Many Polymorphic Relations
     * @return [type] [description]
     */
    public function events()
    {
        return $this->morphToMany(Event::class, 'logger', 'payment_logs', null, 'payment_event_id');
    }

    /**
     * Reverse status
     * @param  boolean $index [description]
     * @return [type]         [description]
     */
    public static function reverseStatus($index = false)
    {
        $list = [
            self::PAY_STATUS_PENDING => 0,
            self::PAY_STATUS_PROCESSING => 1,
            self::PAY_STATUS_SUCCESS => 2,
            self::PAY_STATUS_CLOSED => 3,
            self::PAY_STATUS_CANELED => 4
        ];
        return $status === true  ? $list : $list[$index];
    }
}
