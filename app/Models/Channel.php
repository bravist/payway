<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
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
    protected $table = 'payment_channels';

    /**
     * Channel has many pay ways
     * @return App\Models\channelPayWay
     */
    public function channelPayWays()
    {
        return $this->hasMany(ChannelPayWay::class, 'payment_channel_id');
    }

    /**
     * Channel has many orders
     * @return App\Models\Order
     */
    public function orders()
    {
        return $this->hasMay(Order::class, 'payment_channel_id');
    }
}
