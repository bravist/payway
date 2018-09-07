<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelPayWay extends Model
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
    protected $table = 'payment_channel_pay_ways';

    /**
     * Channel has own channel pay way
     * @return [type] [description]
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'payment_channel_id');
    }
}
