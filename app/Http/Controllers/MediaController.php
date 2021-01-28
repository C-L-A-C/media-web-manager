<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\{Storage, Collection};
use App\{Configuration, ResourceDirectory};
use lecodeurdudimanche\PHPPlayer\{Song, Manager};

class MediaController extends Controller
{
    private $musicManager;

    public function __construct()
    {
        //TODO: optimise that (done at each request)
        $this->musicManager = new Manager(config("player.cache"));

        $this->musicManager->setConfigurationOption("format", config("player.format"));
        $this->musicManager->setConfigurationOption("caching_time", config("player.caching_time"));
    }

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
        $status = $this->musicManager->syncPlaybackStatus();

        return response()->json(['error' => 'no', 'data' => ['status' => $status, 'playingTime' => $status->getPlayingTime()]]);
    }

    public function addSong(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'string', Rule::in(Song::AVAILABLE_TYPES)],
            'uri' => 'required|string',
            'pos' => 'nullable|int'
        ]);

        //TODO: sanity check over uri to prevent local files scanning or server side request exploit

        if (isset($data['pos']))
            $this->musicManager->queueMusic($data['type'], $data['uri'], $data['pos']);
        else
            $this->musicManager->queueMusic($data['type'], $data['uri']);

        return response()->json(['error' => 'no']);

    }

    public function removeSong(Request $request)
    {
        throw new \Exception("removeSong not implemented");
    }

    public function setMode(Request $request)
    {
        $data = $request->validate(['mode' => 'required|in:bluetooth,playlist']);

        Configuration::updateOrCreate(['name' => 'mode'], ['value' => $data['mode']]);

        return response()->json(['error' => 'no']);
    }

    public function getMode(Request $request)
    {
        $mode = Configuration::findOrFail('mode');

        return response()->json(['error' => 'no', 'mode' => $mode['value']]);
    }

    public function __call($name, $args)
    {
        if (in_array($name, ['doPlay', 'doPause', 'doNext', 'doPrev']))
            $this->musicManager->{strtolower(substr($name, 2))}($args);
        else
            throw new \BadMethodCallException("La méthode MediaController@$name n'existe pas");

    }

}
