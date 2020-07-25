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
        Route::post('play', 'MediaController@play')->name('play');
        Route::get('list', 'MediaController@list')->name('list');
        Route::get('source', 'MediaController@getSource')->name('getSource');
        Route::post('source', 'MediaController@setSources')->name('setSource');
        Route::get('sources', 'MediaController@getAvailableSources')->name('getSourceList');
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
    Route::post('resume', 'PlaybackController@pause')->name('pause');
    Route::post('next', 'PlaybackController@next')->name('next');
    Route::post('previous', 'PlaybackController@previous')->name('previous');
});
