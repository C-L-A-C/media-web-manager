<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use lecodeurdudimanche\PHPBluetooth\{Command, Manager};


class BluetoothController extends Controller
{
    private $manager;

    public function listDevices()
    {
        $this->manager = new Manager(true, true);
        $this->manager->scanDevices();
        usleep(1000000);
        $this->manager->updateBluetoothInfo();
        $bt = $this->manager->getBluetoothInfo();
        return response()->json($bt->getDevices());
    }

    public function isMuted() : bool
    {
        $status = (new Command("pulseaudio-ctl full-status"))->execute();
        var_dump($status);
        list($volume, $sinkMuted, $sourceMuted) = explode(" ", $status);
        return $sourceMuted == "yes";
    }

    private function toggleMute()
    {
        var_dump((new Command("pulseaudio-ctl mute-input"))->execute());
    }

    public function mute()
    {
        if (! $this->isMuted())
            $this->toggleMute();
        return response()->json(["error" => "no"]);
    }

    public function unmute()
    {
        if (! $this->isMuted())
            $this->toggleMute();
        return response()->json(["error" => "no"]);
    }
}
