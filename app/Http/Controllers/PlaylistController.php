<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlaylistEntry;

class PlaylistController extends Controller
{
    public static function getTypes()
    {
        return ['youtube', 'file'];
    }

    public function playSong(PlaylistEntry $song)
    {
        
    }
}
