<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InternalRequestOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $logger;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request, $logger)
    {
        $this->request = $request;
        $this->logger = $logger;
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
