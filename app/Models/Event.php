<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
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
    protected $table = 'payment_events';

    /**
     * Many To Many Polymorphic Relations
     * @return [type] [description]
     */
    public function orders()
    {
        return $this->morphedByMany(Order::class, 'logger');
    }

    /**
     * Many To Many Polymorphic Relations
     * @return [type] [description]
     */
    public function refunds()
    {
        return $this->morphedByMany(Refund::class, 'logger');
    }
}
