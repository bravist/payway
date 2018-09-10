<?php

namespace App\Listeners;

use App\Events\InternalRequestOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Log;

class SaveInternalRequestOrderLog
{
    const EVENT_NAME = 'internal_request_order';
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InternalRequestOrder  $event
     * @return void
     */
    public function handle(InternalRequestOrder $event)
    {
        
    }
}
