<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ExternalQueryOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $logger;
    public $status;
    public $request;
    public $response;

    const PAY_STATUS_PAID = 'paid';
    const PAY_STATUS_EXPIRED = 'expired';
    const PAY_STATUS_QUERY = 'query';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($logger, $status, $request, $response)
    {
        $this->logger = $logger;
        $this->status = $status;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
