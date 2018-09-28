<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    const INTERNAL_REQUEST_ORDER = 'internal_request_order';
    const EXTERNAL_REQUEST_ORDER = 'external_request_order';
    const INTERNAL_REQUEST_REFUND = 'internal_request_refund';
    const EXTERNAL_REQUEST_REFUND = 'external_request_refund';
    const EXTERNAL_WEHOOK = 'external_wehook';
    const INTERNAL_WEBHOOK = 'internal_webhook';
    const EXTERNAL_QUERY_PAID_ORDER = 'external_query_paid_order';
    const EXTERNAL_QUERY_EXPIRED_ORDER = 'external_query_expired_order';
    const EXTERNAL_QUERY_REFUND = 'external_query_refund';
    const EXTERNAL_QUERY_ORDER = 'external_query_order';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
