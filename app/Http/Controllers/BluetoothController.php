<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use lecodeurdudimanche\PHPBluetooth\Manager;


class BluetoothController extends Controller
{
    private $manager;

    public function __construct()
    {
        $this->manager = new Manager(true, true);
        $this->manager->scanDevices();
        //usleep(5000000);
    }

    public function listDevices()
    {
        $this->manager->updateBluetoothInfo();
        $bt = $this->manager->getBluetoothInfo();
        return response()->json($bt->getDevices());
    }
}
