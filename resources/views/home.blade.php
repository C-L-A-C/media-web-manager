<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <title>Media center - Web manager</title>
    </head>
    <body class='bg-dark text-white'>
        <div class='d-none api-resources'>
            <span id="deviceOperationAPI" data-route="{{ route("bluetooth.deviceOperation") }}"></span>
            <span id="changeModeAPI" data-set="{{ route("media.audio.config.setMode")}}" data-get="{{ route("media.audio.config.getMode") }}"></span>
            <span id="youtubeAPI" data-key="{{ env("YOUTUBE_API_KEY") }}"></span>

        </div>
        <!-- Modal -->
        <div class="modal fade text-dark" data-backdrop="static" data-keyboard="false" id="searchModal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Search results</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <div id='search-result-template' style="display:none">
                  <div class='col-12 mb-2 search-result'>
                      <div class='card'>
                          <div class='card-body bg-light'>
                              <div class='row'>
                                  <span class='id d-none'></span>
                                  <div class='col-6'><img class='w-100 thumbnail'></div>
                                  <div class='col-6'>
                                      <div class='row h-100' style='align-content:space-evenly;'>
                                          <div class='col-12 title'></div>
                                          <div class='col-12 author font-weight-bold'></div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
                <div id='search-results' class='row'>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
      </div>

        <div class='container p-4'>
            <h1 class='text-center'>Web manager</h1>
            <hr class='my-4 bg-light'>
            <div class='info row'>
                <div class='col-12'>
                    <div class='line-section'>
                        <h3 class='d-inline mr-2'>Auto-sync :</h3>
                        <button id='sync-button' class='btn btn-light'>
                            <div class='icon-container' title='Enabled' style='display:none'>
                                <span class="fa-stack">
                                  <i class="fas fa-sync-alt fa-stack-1x"></i>
                                </span>
                            </div>
                            <div class='icon-container' title='Disabled'>
                                <span class="fa-stack">
                                  <i class="fas fa-sync-alt fa-stack-1x"></i>
                                  <i class="fas fa-slash fa-stack-1x"></i>
                                </span>
                            </div>
                        </button>
                    </div>
                </div>
                <div class='col-12'>
                    <div class='section'>
                        <div class='row'>
                            <div class='col-10'>
                                <h2>Media configuration</h2>
                            </div>
                            <div class='col'>
                                <input id='checkbox-media' type='checkbox'>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col'>
                                <h3>Add a song</h3>
                                <form id='song-add-form' action='{{ route('media.audio.addSong') }}' method='POST'>
                                    <div class='row d-none d-md-flex'>
                                        <div class='col-12 col-md-3'>
                                                <label for='song-type'>Type</label>
                                        </div>
                                        <div class='col-12 col-md-7'>
                                                <label for='song-uri'>URL / Path</label>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-12 col-md-3'>
                                            <label for='song-type' class='d-md-none'>Type</label>
                                            <select id='song-type' name='type' class='custom-select' required>
                                                <option value='youtube'>Youtube (or other video services)</option>
                                                <option value='url'>Direct URL</option>
                                                <option value='local'>Local</option>
                                            </select>
                                        </div>
                                        <div class='col-12 col-md-5'>
                                            <label for='song-uri' class='d-md-none'>URL / Path</label>
                                            <input id='song-uri' name='uri' class='form-control' placeholder='URL / mots-clefs de recherche' required>
                                        </div>
                                        <div class='col-12 col-md-4'>
                                            <button class='btn btn-outline-primary mb-2' id='song-add'>Add</button>
                                            <button id='searchModalButton' type="button" class="btn btn-outline-primary mb-2" data-toggle="modal" data-target="#searchModal">
                                              Search
                                            </button>
                                            <button id='updateScroll' type="button" class="btn btn-outline-secondary mb-2">
                                              Scroll to current
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class='col-12'>
                                <div id='playlist-song-template' style="display:none">
                                    <div class='col-12 mb-2'>
                                        <div class='card'>
                                            <div class='card-body bg-not-so-dark'>
                                                <div class='row'>
                                                    <div class='col-1 index'></div>
                                                    <div class='col-1 status'>
                                                        <i class='icon-playing fas fa-play' style='display:none'></i>
                                                        <i class='icon-paused fas fa-pause' style='display:none'></i>
                                                    </div>
                                                    <div class='col-6 name'></div>
                                                    <div class='col-4 time-data'>
                                                        <span class='time'></span><span class='length'></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id='playlist-songs' class='mt-4' data-route="{{ route('media.audio.getPlaylist') }}" class='row'>
                                    <div class='col-12'><em class='d-block m-auto'>No songs</em></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-12'>
                    <div class='section'>
                        <div class='row'>
                            <div class='col-10'>
                                <h2>Bluetooth configuration</h2>
                            </div>
                            <div class='col'>
                                <input id='checkbox-bluetooth' type='checkbox'>
                            </div>
                        </div>
                        <div class='d-flex'>
                            <h3>Devices</h3>
                            <div id='bt-devices-refresh-icon' class='ml-2' style='display:none'><img src='{{asset('img/loading.svg')}}' width="40"></img></div>
                        </div>
                        <div class='m-1'>
                            <div id='bt-device-template' style="display:none">
                                <div class='col-6 mb-2'>
                                    <div class='card'>
                                        <div class='card-body bg-not-so-dark'>
                                            <div>
                                                <span class='class-icons mr-2'>
                                                    <i class='fas fa-mobile class-smartphone' style='display:none'></i>
                                                    <i class='fas fa-phone-alt class-phone' style='display:none'></i>
                                                    <i class='fab fa-usb class-peripheral' style='display:none'></i>
                                                    <i class='fas fa-headphones-alt class-speaker' style='display:none'></i>
                                                    <i class='fas fa-laptop class-computer' style='display:none'></i>
                                                    <i class='fas fa-tv class-tv' style='display:none'></i>
                                                    <i class='fas fa-question class-other' style='display:none'></i>
                                                </span>
                                                <span class='info-name'>
                                                    <span style='font-style:italic'>No name</span>
                                                </span>
                                            </div>
                                            <small class='info-mac'></small>
                                        </div>
                                        <div class='card-footer icon-container text-dark' style='display:none'>
                                            <!-- <i class='fas fa-broadcast-tower info-available mr-2' style='display:none' title='In range'></i> -->
                                            <i class='fas fa-link info-paired mr-2' style='display:none' title='Paired'></i>
                                            <i class='fas fa-wifi info-connected mr-2' style='display:none' title='Connected'></i>
                                            <span class='info-rssi mr-2' title="Not in range">
                                                    <i class="fas fa-signal text-muted"></i>
                                                    <i class="fas fa-signal signal-overlay" style='width:0'></i>
                                            </span>
                                            <span class='vr'></span>
                                            <span>
                                                <button class='btn btn-sm btn-outline-danger block-device' title='Block device'>
                                                     <i class="fas fa-ban block-icon"></i>
                                                     <i class='fas fa-check unblock-icon'></i>
                                                </button>
                                                <button class='btn btn-sm btn-outline-danger disconnect-device' title='Disconnect device' style="display:none">
                                                    <div class='fa-stack fa-stack-small'>
                                                        <i class='fas fa-wifi fa-stack-1x'></i>
                                                        <i class='fas fa-slash fa-stack-1x'></i>
                                                    </div>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id='bt-devices' data-route="{{ route('bluetooth.listDevices') }}" class='row'>
                                <div class='col'>No devices</div>
                            </div>
                        </div>
                        <h3>Actions<h3>
                        <div class='m-1'>
                            <div id='bt-status-button' data-route="{{route('bluetooth.status')}}">
                                <button class='btn btn-outline-light action disable' data-action="{{ route('bluetooth.disable') }}" style='display:none'><i class="fas fa-volume-mute"></i></button>
                                <button  class='btn btn-outline-light action enable' data-action="{{route('bluetooth.enable')}}"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='playback-controls mt-4 bg-dark py-2'>
            <div class='col-12 d-flex justify-content-center'>
                <button class='btn btn-outline-secondary mx-1' data-route='{{ route('controls.previous') }}' title='Previous song'><i class='fas fa-backward'></i></button>
                <button class='btn btn-outline-secondary mx-1' data-route='{{ route('controls.resume') }}' title='Resume'><i class='fas fa-play'></i></button>
                <button class='btn btn-outline-secondary mx-1' data-route='{{ route('controls.pause') }}' title='Pause'><i class='fas fa-pause'></i></button>
                <button class='btn btn-outline-secondary mx-1'data-route='{{ route('controls.next') }}' title='Next song'><i class='fas fa-forward'></i></button>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>


        <script src="{{ asset('js/app.js')}}"></script>
    </body>
</html>
