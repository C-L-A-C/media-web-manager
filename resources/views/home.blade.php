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
        <div class='container p-4'>
            <h1 class='text-center'>Web manager</h1>
            <hr class='my-4 bg-light'>
            <div class='info row'>
                <div class='col-12 col-md-6'>
                    <div class='section'>
                        <h2>Media configuration</h2>
                    </div>
                </div>
                <div class='col-12 col-md-6'>
                    <div class='section'>
                        <h2>Bluetooth configuration</h2>
                        <h3>Devices</h3>
                        <div class='m-1'>
                            <textarea id='bt-devices' class='from-control w-100' disabled style="resize:none" rows="10"></textarea>
                        </div>
                        <div class='d-flex justify-content-center'>
                            <button id='bt-devices-refresh' class='btn btn-outline-light' data-route="{{ route('bluetooth.listDevices') }}">Rafraîchir</button>
                            <div id='bt-devices-refresh-icon' class='ml-2' style='display:none'><img src='{{asset('img/loading.svg')}}' width="40"></img></div>
                        </div>
                        <h3>Actions<h3>
                        <div class='m-1'>
                            <button id='bt-mute' class='btn btn-outline-light ajax-trigger' data-action="{{route('bluetooth.disable')}}"><i class="fas fa-volume-mute"></i></button>
                            <button id='bt-unmute' class='btn btn-outline-light ajax-trigger' data-action="{{route('bluetooth.enable')}}"><i class="fas fa-volume-up"></i></button>
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
