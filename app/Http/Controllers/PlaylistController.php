<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function getPlaylist()
    {
        $playlist = PlaylistEntry::where('active', 1)->get();
        return response()->json($playlist);
    }

    public function addSong(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'string', Rule::in(PlaylistController::getTypes())],
            'path' => 'required|string'
        ]);

        $song = new PlaylistEntry($data);
        $song->save();

        //Add event playlist_entry added

        return response()->json(['error' => 'no']);

    }

    public function removeSong(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:playlist_entries'
        ]);
        PlaylistEntry::find($data['id']);

        //Add event playlist_entry removed

        return response()->json(['error' => 'no']);
    }
}
