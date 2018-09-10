<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Requests\Api\OrderRequest;
use App\Models\Order;

class InternalRequestOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $order;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OrderRequest $request, Order $order)
    {
        $this->request = $request;
        $this->order = $order;
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
