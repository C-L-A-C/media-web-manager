<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;

class PlaybackController extends Controller
{
    private $mode;
    private $controller;
    private const CONTROLLERS = [
        'playlist' => MediaController::class,
        'bluetooth' => BluetoothController::class
    ];

    public function __construct($mode = null)
    {
        $this->mode = $mode ?? Configuration::findOrFail('mode')->value;
        if (in_array($this->mode, self::CONTROLLERS))
            throw new \Exception("Invalid mode $this->mode");
        $class = self::CONTROLLERS[$this->mode];
        $this->controller = new $class;
    }

    public function getStatus()
    {
        throw new \Exception("getStatus not implemented");
    }

    public function setVolume(int $volume)
    {
        throw new \Exception("setVolume not implemented");
    }

    public function resume()
    {
        $this->controller->doPlay();
        return response()->json(['error' => 'no']);
    }

    public function pause()
    {
        $this->controller->doPause();
        return response()->json(['error' => 'no']);
    }

    public function next()
    {
        $this->controller->doNext();
        return response()->json(['error' => 'no']);
    }

    public function previous()
    {
        $this->controller->doPrev();
        return response()->json(['error' => 'no']);
    }
}
