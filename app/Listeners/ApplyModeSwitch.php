<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ConfigurationUpdated;
use App\Configuration;
use App\Http\Controllers\PlaybackController;

class ApplyModeSwitch
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
     * @param  object  $event
     * @return void
     */
    public function handle(ConfigurationUpdated $event)
    {
        $new = $event->getNew();
        $prev = $event->getPrev();
        if ($new->name == 'mode' && $new->value != $prev->value)
        {
            (new PlaybackController($prev->value))->pause();
            (new PlaybackController($new->value))->resume();
        }
    }
}
