<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotifyToWeb implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $title;
    public $content;
    public $action;
    public $onchat;

    public function __construct($id, $title, $content, $action, $onchat)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->action = $action;
        $this->onchat = $onchat;
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
