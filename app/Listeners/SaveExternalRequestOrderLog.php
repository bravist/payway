<?php

namespace App\Listeners;

use App\Events\ExternalRequestOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ry\Model\Payway\Log;
use Ry\Model\Payway\Event;

class SaveExternalRequestOrderLog
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
     * @param  ExternalRequestOrder  $event
     * @return void
     */
    public function handle(ExternalRequestOrder $event)
    {
        $paymentEvent = Event::where('name', Event::EXTERNAL_REQUEST_ORDER)->first();
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
