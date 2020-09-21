<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use lecodeurdudimanche\PHPBluetooth\{Command, Manager, Device};


class BluetoothController extends Controller
{
    private $manager;

    public function listDevices()
    {
        $this->manager = new Manager(true, true);
        $this->manager->scanDevices();
        $this->manager->updateBluetoothInfo();
        $bt = $this->manager->getBluetoothInfo();
        return response()->json($bt->getDevices());
    }

    //TODO: fix user permissions (launch PA service from http user ? manage to grant PA control to http user ?)
    //TODO: Do regex search over the data
    public function getPAStatus() : array
    {
        $home = env("SERVER_HOME", "/home/http");
        (new Command("HOME=$home pulseaudio"))->execute();
        $status = (new Command("HOME=$home pacmd list-sources"))->execute();
        var_dump($status);
        return ["isMuted" => false, "id" => -1];
    }

    public function doDeviceOperation(Request $request)
    {
        $data = $request->validate([
            'action' => 'required|in:block,unblock,disconnect',
            'mac' => 'required'
        ]);

        $this->manager = new Manager(true, true);

        $device = new Device($data['mac']);
        switch($data['action'])
        {
            case 'unblock':
            case 'block':
                $this->manager->blockDevice($device, $data['action'] == 'block');
                break;
            case 'disconnect':
                $this->manager->connect($device, false);
                break;
        }
        return response()->json(["error" => "no"]);
    }

    public function doMute(bool $mute) : void
    {
        $id = $this->getPAStatus()['id'];
        $mute = intval($mute);
        (new Command("pacmd set-source-mute $id $mute"))->execute();
    }

    public function mute()
    {
        $this->doMute(true);
        return response()->json(["error" => "no"]);
    }

    public function unmute()
    {
        $this->doMute(false);
        return response()->json(["error" => "no"]);
    }

    public function getStatus()
    {
        $data = $this->getPAStatus();
        return response()->json($data);
    }
}
