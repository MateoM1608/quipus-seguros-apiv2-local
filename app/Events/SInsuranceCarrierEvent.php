<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Models
use App\Models\Policy\SInsuranceCarrier;

class SInsuranceCarrierEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $insuranceCarrier;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SInsuranceCarrier $insuranceCarrier)
    {
        $this->insuranceCarrier = $insuranceCarrier;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('insuranceCarrier.' . auth('api')->user()->connection);
    }
}
