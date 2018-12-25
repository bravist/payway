<?php

namespace App\Listeners;

use App\Events\ExternalRequestRefund;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ry\Model\Payway\Log;
use Ry\Model\Payway\Event;

class SaveExternalRequestRefundLog
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
     * @param  ExternalRequestRefund  $event
     * @return void
     */
    public function handle(ExternalRequestRefund $event)
    {
        $paymentEvent = Event::where('name', Event::EXTERNAL_REQUEST_REFUND)->first();
        Log::create([
            'payment_event_id' => $paymentEvent->id,
            'logger_id' => $event->logger->id,
            'logger_type' => $event->logger->getMorphClass(),
            'request_url' => 'https://api.mch.weixin.qq.com/secapi/pay/refund',
            'request' => json_encode($event->request),
            'response' => json_encode($event->response)
        ]);
    }
}
