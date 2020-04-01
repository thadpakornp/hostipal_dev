<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivateChart implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $url;
    public $status;
    public $id;

    public function __construct($status, $url, $id)
    {
        $this->status = $status;
        $this->url = $url;
        $this->id = $id;
    }

    public function broadcastOn()
    {
        return ['chart-channel'];
    }

    public function broadcastAs()
    {
        return 'chart-event';
    }
}
