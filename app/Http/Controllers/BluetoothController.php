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
    public function getPAStatus() : ?array
    {
        $home = env("SERVER_HOME", "/home/http");
        (new Command("HOME=$home pulseaudio -D"))->execute();
        $status = (new Command("HOME=$home pacmd list-sources"))->execute();

        if ($status["err"])
            return null;

        foreach(explode("index: ", $status["out"]) as $source) {
            $index = intval($source);

            //Check if source is a bluetooth device
            if (preg_match("/bluez_source\..*\.a2dp_source/", $source)) {
                    preg_match("/muted: ((?:yes)|(?:no))/", $source, $matches);
                    $muted = $matches[1] == "yes";
                    return ["isMuted" => $muted, "id" => $index];
            }
        }
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

        if ($data === false)
            $data = ["error" => "pulseaudio"];
        else
            $data['error'] = "no";

        return response()->json($data);
    }
}
