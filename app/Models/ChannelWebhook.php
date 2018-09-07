<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelWebhook extends Model
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
    protected $table = 'payment_channel_webhooks';
}
