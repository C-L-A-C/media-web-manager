<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Configuration;

class ConfigurationUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $prev, $new;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Configuration $c)
    {
        $this->prev = Configuration::find($c->name);
        $this->new = $c;
    }

    public function getPrev() : Configuration
    {
        return $this->prev;
    }

    public function getNew() : Configuration
    {
        return $this->new;
    }
}
