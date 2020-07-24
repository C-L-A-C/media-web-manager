<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
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
                        <textarea id='bt-devices' class='custom' disabled style="resize:none"></textarea>
                        <button id='bt-devices-refresh' class='btn btn-outline-light' data-route="{{ route('bluetooth.listDevices') }}">Rafraîchir</button>
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
