<?php

namespace App\Listeners;

use App\Events\InternalWebhook;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Log;
use App\Models\Event;

class SaveInternalWebhookLog
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
     * @param  InternalWebhook  $event
     * @return void
     */
    public function handle(InternalWebhook $event)
    {
        $paymentEvent = Event::where('name', Event::INTERNAL_WEBHOOK)->first();
        Log::create([
            'payment_event_id' => $paymentEvent->id,
            'logger_id' => $event->logger ? $event->logger->id : 0,
            'logger_type' => $event->logger ? $event->logger->getMorphClass() : '',
            'request_url' => $event->request->fullUrl(),
            'request' => json_encode($event->request->all()),
            'response' => json_encode($event->response)
        ]);
    }
}
