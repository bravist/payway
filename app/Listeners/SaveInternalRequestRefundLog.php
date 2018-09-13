<?php

namespace App\Listeners;

use App\Events\InternalRequestRefund;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Log;
use App\Models\Event;

class SaveInternalRequestRefundLog
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
     * @param  InternalRequestRefund  $event
     * @return void
     */
    public function handle(InternalRequestRefund $event)
    {
        $paymentEvent = Event::where('name', Event::INTERNAL_REQUEST_REFUND)->first();
        Log::create([
            'payment_event_id' => $paymentEvent->id,
            'logger_id' => $event->logger->id,
            'logger_type' => $event->logger->getMorphClass(),
            'request_url' => $event->request->fullUrl(),
            'request' => json_encode($event->request),
            'response' => json_encode($event->response)
        ]);
    }
}
