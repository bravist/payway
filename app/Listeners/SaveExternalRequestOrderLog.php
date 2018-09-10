<?php

namespace App\Listeners;

use App\Events\ExternalRequestOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Log;

class SaveExternalRequestOrderLog
{
    const EVENT_NAME = 'external_request_order';
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
     * @param  ExternalRequestOrder  $event
     * @return void
     */
    public function handle(ExternalRequestOrder $event)
    {
        $paymentEvent = Event::where('name', self::EVENT_NAME)->first();
        Log::create([
            'payment_event_id' => $paymentEvent->id,
            'logger_id' => $event->order->id,
            'logger_type' => $event->order->getMorphClass(),
            'request_url' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'request' => json_encode($event->request),
            'response' => $event->response
        ]);
    }
}
