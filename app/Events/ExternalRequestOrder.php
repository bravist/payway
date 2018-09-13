<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ExternalRequestOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $response;
    public $logger;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($logger, $request, $response)
    {
        $this->logger = $logger;
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
