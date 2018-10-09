<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_CLOSED = 'closed';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = ['prepay'];
    
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
     * Order
     * @return [type] [description]
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'payment_order_id');
    }

    /**
     * Polymorphic Relations
     * @return [type] [description]
     */
    public function webhooks()
    {
        return $this->morphMany(Webhook::class, 'webhookable');
    }

    /**
     * Polymorphic Relations
     * @return [type] [description]
     */
    public function channelWebhooks()
    {
        return $this->morphMany(ChannelWebhook::class, 'webhookable');
    }

    /**
     * Prepay
     * @return [type] [description]
     */
    public function getPrepayAttribute()
    {
        $event = Event::where('name', Event::EXTERNAL_REQUEST_REFUND)->first();
        return $this->logs()->where('payment_event_id', $event->id)->first();
    }
}
