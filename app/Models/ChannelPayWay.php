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
     * Channel has own channel pay way
     * @return App\Models\Channel
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'payment_channel_id');
    }
}
