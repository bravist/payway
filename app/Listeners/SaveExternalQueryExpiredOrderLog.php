<?php

namespace App\Listeners;

use App\Events\ExternalQueryOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ry\Model\Payway\Log;
use Ry\Model\Payway\Event;

class SaveExternalQueryExpiredOrderLog
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
     * @param  ExternalQueryOrder  $event
     * @return void
     */
    public function handle(ExternalQueryOrder $event)
    {
        if ($event->status = ExternalQueryOrder::PAY_STATUS_EXPIRED) {
            $paymentEvent = Event::where('name', Event::EXTERNAL_QUERY_EXPIRED_ORDER)->first();
            Log::create([
                'payment_event_id' => $paymentEvent->id,
                'logger_id' => $event->logger->id,
                'logger_type' => $event->logger->getMorphClass(),
                'request_url' => 'https://api.mch.weixin.qq.com/pay/orderquery',
                'request' => json_encode($event->request),
                'response' => json_encode($event->response)
            ]);
        }
    }
}
