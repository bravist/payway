<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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

}
