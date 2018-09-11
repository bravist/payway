<?php

namespace App\Listeners;

use App\Events\InternalRequestOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Log;
use App\Models\Event;

class SaveInternalRequestOrderLog
{
    
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
        $paymentEvent = Event::where('name', Event::INTERNAL_REQUEST_ORDER)->first();
        Log::create([
            'payment_event_id' => $paymentEvent->id,
            'logger_id' => $event->order->id,
            'logger_type' => $event->order->getMorphClass(),
            'request_url' => $event->request->fullUrl(),
            'request' => json_encode($event->request->all()),
            'response' => ''
        ]);
    }
}
