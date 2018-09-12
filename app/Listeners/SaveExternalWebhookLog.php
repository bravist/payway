<?php

namespace App\Listeners;

use App\Events\ExternalWebhook;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Log;
use App\Models\Event;

class SaveExternalWebhookLog
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
     * @param  ExternalWebhook  $event
     * @return void
     */
    public function handle(ExternalWebhook $event)
    {
        $paymentEvent = Event::where('name', Event::EXTERNAL_WEHOOK)->first();
        Log::create([
            'payment_event_id' => $paymentEvent->id,
            'logger_id' => $event->logger->id,
            'logger_type' => $event->logger->getMorphClass(),
            'request_url' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'request' => json_encode($event->request),
            'response' => json_encode($event->response)
        ]);
    }
}
