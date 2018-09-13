<?php

namespace App\Listeners;

use App\Events\ExternalQueryRefund;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveExternalQueryRefundLog
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
     * @param  ExternalQueryRefund  $event
     * @return void
     */
    public function handle(ExternalQueryRefund $event)
    {
        $paymentEvent = Event::where('name', Event::EXTERNAL_QUERY_REFUND)->first();
        Log::create([
            'payment_event_id' => $paymentEvent->id,
            'logger_id' => $event->logger->id,
            'logger_type' => $event->logger->getMorphClass(),
            'request_url' => 'https://api.mch.weixin.qq.com/pay/refundquery',
            'request' => json_encode($event->request),
            'response' => json_encode($event->response)
        ]);
    }
}
