<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\{Storage, Collection};
use App\{PlaylistEntry, ResourceDirectory};

class MediaController extends Controller
{
    public function listFiles()
    {
        $dirs = ResourceDirectory::where('type', 'audio')->get();
        $files = new Collection;

        foreach($dirs as $dir)
            $files = $files->append(Storage::allFiles($dir));

        return response()->json($files);
    }

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
