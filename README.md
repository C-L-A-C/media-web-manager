# A web interface controlled media center

https://wiki.archlinux.org/index.php/PulseAudio/Examples#Allowing_multiple_users_to_use_PulseAudio_at_the_same_time

Set up a home for the http user
Configure PA for the http user:
In `$HOME/.config/pulse/client.conf` add `default-server = unix:/tmp/pulse-socket`

Configure the main PA server :
In `/etc/pulse/default.pa` add `load-module module-native-protocol-unix auth-group=http socket=/tmp/pulse-socket`
Replace auth-group=http by auth-anonymous=1 if you want to give access to PA for other users

This can be done with the artisan command `setup:pulse`

Restart pulseaudio

Bluetooth : https://www.raspberrypi.org/forums/viewtopic.php?t=108581

A2DP: https://wiki.debian.org/BluetoothUser/a2dp

Pulse: https://askubuntu.com/questions/28176/how-do-i-run-pulseaudio-in-a-headless-server-installation


TODO:
- Figure out if we need to autospawn or systemd/cron `pulseaudio -D`
 - Resolve PA administration issues
 - Control bt playback https://scribles.net/controlling-bluetooth-audio-on-raspberry-pi/
 - Retrieve currently playing file
 - Play local files (possible with pacmd)
 - Allow only one sink to be played at once
 - Add available devices, and name + RSSI of bluetooth Devices
 - Add action to devices
