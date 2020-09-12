<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('media')->name('media.')->group(function() {
    Route::prefix('audio')->name('audio.')->group(function() {
        // Files
        Route::get('files', 'MediaController@listFiles')->name('listFiles');

        // Playlist
        Route::get('playlist', 'MediaController@getPlaylist')->name('getPlaylist');
        Route::post('song', 'MediaController@addSong')->name('addSong');
        Route::delete('song', 'MediaController@removeSong')->name('removeSong');

        //Set audio mode : bluetooth, playlist, none
        Route::prefix('config')->name('config.')->group(function() {
            Route::get('mode', 'MediaController@getMode')->name('getMode');
            Route::put('mode', 'MediaController@setMode')->name('setMode');
        });
    });
});

Route::prefix('bluetooth')->name('bluetooth.')->group(function() {
    Route::post('disable', 'BluetoothController@mute')->name('disable');
    Route::post('enable', 'BluetoothController@unmute')->name('enable');

    Route::get('status', 'BluetoothController@getStatus')->name('status');
    Route::get('devices', 'BluetoothController@listDevices')->name('listDevices');
    Route::post('deviceOperation', 'BluetoothController@doDeviceOperation')->name('deviceOperation');
});

Route::prefix('controls')->name('controls.')->group(function() {

    Route::get('status', 'PlaybackController@getStatus')->name('status');
    Route::post('volume/{}', 'PlaybackController@setVolume')->name('volume');
    Route::post('resume', 'PlaybackController@resume')->name('resume');
    Route::post('pause', 'PlaybackController@pause')->name('pause');
    Route::post('next', 'PlaybackController@next')->name('next');
    Route::post('previous', 'PlaybackController@previous')->name('previous');
});
