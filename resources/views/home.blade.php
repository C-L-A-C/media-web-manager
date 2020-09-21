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
        </div>
        <div class='container p-4'>
            <h1 class='text-center'>Web manager</h1>
            <hr class='my-4 bg-light'>
            <div class='info row'>
                <div class='col-12'>
                    <div class='line-section'>
                        <h3 class='d-inline mr-2'>Auto-sync :</h3>
                        <button id='sync-button' class='btn btn-light'>
                            <div class='icon-container' title='Enabled'>
                                <span class="fa-stack">
                                  <i class="fas fa-sync-alt fa-stack-1x"></i>
                                </span>
                            </div>
                            <div class='icon-container' title='Disabled' style='display:none'>
                                <span class="fa-stack">
                                  <i class="fas fa-sync-alt fa-stack-1x"></i>
                                  <i class="fas fa-slash fa-stack-1x"></i>
                                </span>
                            </div>
                        </button>
                    </div>
                </div>
                <div class='col-12 col-md-6'>
                    <div class='section'>
                        <h2>Media configuration</h2>
                    </div>
                </div>
                <div class='col-12 col-md-6'>
                    <div class='section'>
                        <h2>Bluetooth configuration</h2>
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
                                                    <i class='fas fa-usb class-peripheral' style='display:none'></i>
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
                                            <i class='fas fa-broadcast-tower info-available mr-2' style='display:none' title='In range'></i>
                                            <i class='fas fa-link info-paired mr-2' style='display:none' title='Paired'></i>
                                            <i class='fas fa-wifi info-connected mr-2' style='display:none' title='Connected'></i>
                                            <i class='fas fa-signal info-rssi mr-2' style='display:none'></i>
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
                <div class='col-12 col-md-6'>
                    <div class='section'>
                        <h2>Playback configuration</h2>
                    </div>
                </div>

            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>


        <script src="{{ asset('js/app.js')}}"></script>
    </body>
</html>
