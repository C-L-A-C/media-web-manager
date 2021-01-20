var btSyncRoutineID = null, playlistSyncRoutineID = null;

$(document).ready(function(){
    refreshMode();
    refreshPlaylist();

    $("#sync-button").click(function(event) {
        let container = $(event.delegateTarget);
        let shouldEnable = btSyncRoutineID === null;
        container.find(".icon-container").toggle();
        enableBTSynchronisation(shouldEnable);
    });

    $("#bt-status-button").click(function(event) {
        let container = $(event.delegateTarget);
        let action = container.find(".btn:visible").data('action');
        container.find(".btn").toggle();
        fetch(action, {method: "POST"});
    });

    $("#song-add-form").submit(function(event) {
        event.preventDefault();

        let form = $(event.delegateTarget);
        let type = form.find("#song-type").val();
        let uri = form.find("#song-uri").val();

        var contains = (string, c) => string.indexOf(c) != -1;
        var looksLikeUrl = string => !contains(string, " ") && contains(string, ".") + contains(string, "/") + contains(string, "?") + contains(string, "wwww") + contains(string, "=") >= 2;

        if (looksLikeUrl(uri))
            doApiCall(form.attr('action'), {type: type, uri: uri, pos: -1}, form.attr('method'), refreshPlaylist);
        else
            youtubeSearch(uri, data => data.items && data.items.length ? setSearchSelection(data.items[0].id.videoId) : "no-op");


        form[0].reset();

    });

    $("#checkbox-media").click(() =>  {changeMode("playlist"); });
    $("#checkbox-bluetooth").click(() => {changeMode("bluetooth"); });

    $(".playback-controls button").click(playbackAction);

    $('#searchModal').on('show.bs.modal', function (event) {
          $(this).find("#search-results").text("Recherche...");
          youtubeSearch($("#song-uri").val(), displaySearchResults);
    });

    $('#song-type').change((event) => {
        let type = $(event.delegateTarget).val();
        if (type == 'youtube')
            $("#searchModalButton").fadeIn(200);
        else
            $("#searchModalButton").fadeOut(200);
    });

});

function youtubeSearch(search, callback)
{
    let key = $("#youtubeAPI").data('key');
    let url = "https://youtube.googleapis.com/youtube/v3/search?" + new URLSearchParams({
        part : 'snippet', type : 'video', q : search, key : key
    });
    fetch(url, {
            headers: {
                'Accept' : 'application/json',
                'Content-Type' : 'application/json'
            }
         })
        .then(response => response.json())
        .then(callback)
        .catch(console.error);
}

function setSearchSelection(videoId)
{
    $("#searchModal").modal('hide');
    $("#song-uri").val("https://www.youtube.com/watch?v=" + videoId);
    $("#song-add-form").submit();
}

function displaySearchResults(data)
{
    let container = $("#search-results");
    let template = $('#search-result-template>div');
    container.html("");

    for (video of data.items)
    {
        let videoData = video.snippet;
        let videoContainer = template.clone();
        videoContainer.data('videoId', video.id.videoId);
        videoContainer.find('.title').text(videoData.title);
        videoContainer.find('.author').text(videoData.channelTitle);
        videoContainer.find('.thumbnail').attr('src', videoData.thumbnails.default.url);
        videoContainer.click((e) => setSearchSelection($(e.delegateTarget).data('videoId')));
        container.append(videoContainer);
    }
}

function playbackAction(event)
{
    let action = $(event.delegateTarget).data('route');
    doApiCall(action, null, "POST", refreshPlaylist);
}

function enablePlaylistSynchronisation(enable = true)
{
    if (enable && !playlistSyncRoutineID)
        playlistSyncRoutineID = setInterval(refreshPlaylist, 2000); //TODO: baisser ça et augmenter de maniere artificielle le compteur
    else if (!enable && playlistSyncRoutineID)
    {
        clearInterval(playlistSyncRoutineID);
        playlistSyncRoutineID = null;
    }
}

function enableBTSynchronisation(enable = true)
{
    if (enable && !btSyncRoutineID)
        btSyncRoutineID = setInterval(syncroniseData, 5000);
    else if (!enable && btSyncRoutineID) {
        clearInterval(btSyncRoutineID);
        btSyncRoutineID = null;
    }
}

function changeMode(mode)
{
    let setModeRoute = $("#changeModeAPI").data("set");
    doApiCall(setModeRoute, {mode : mode}, "PUT", () => {
        refreshMode();
        refreshPlaylist();
    });
}

function refreshMode()
{
    let getModeRoute = $("#changeModeAPI").data("get");
    doApiCall(getModeRoute, null, "GET", (data) => {
        if (data.error == "no")
        {
            $("#checkbox-bluetooth,#checkbox-media").prop("checked", false);
            if (data.mode == "bluetooth")
                $("#checkbox-bluetooth").prop("checked", true);
            else if (data.mode == "playlist")
                $("#checkbox-media").prop("checked", true);
        }
    });
}

function syncroniseData()
{
    refreshDevicesList();
    refreshBluetoothStatus();
}

function refreshBluetoothStatus()
{
    let route = $("#bt-status-button").data("route");
    fetch(route)
        .then(data => data.json())
        .then(bt => {
            let toShow = bt.muted ? "disable" : "enable";
            let toHide = bt.muted ? "enable" : "disable";
            $("#bt-status-button ." + toShow).show();
            $("#bt-status-button ." + toHide).hide();
        })
        .catch(err => console.error(err));
}

function refreshDevicesList()
{
    let route = $("#bt-devices").data('route');
    $("#bt-devices-refresh-icon").show();

    fetch(route)
        .then(data => data.json())
        .then(displayDevices)
        .catch(e => {
            console.error(e);
            displayDevices(false);
        });
}

function refreshPlaylist()
{
    let route = $("#playlist-songs").data("route");
    doApiCall(route, null, "GET", displayPlaylist);
}

function disconnectDevice(e)
{
    doBluetoothApiCall("disconnect", e);
}

function blockDevice(e)
{
    doBluetoothApiCall($(e.delegateTarget).data('blocked') === "yes" ? "unblock" : "block", e);
}

function doBluetoothApiCall(action, event)
{
    let mac = $(event.delegateTarget).data("mac");
    let route = $("#deviceOperationAPI").data("route");

    doApiCall(route, {action:action, mac: mac}, "POST", refreshDevicesList);
}

function doApiCall(route, data, method, done)
{
    fetch(route, {
        method: method,
        body: data ? new URLSearchParams(data) : null
    })
    .then(data => data.json())
    .then(status => {
        if (done) done(status);
        displayError(status.error);
    })
    .catch(error => {
        console.error(error);
        displayError("server");
    });
}

function timeStrFromSecs(secs)
{
    return parseInt(secs / 60) + ":" + (secs % 60 >= 10 ? "" : "0") + (secs % 60);
}

function displayPlaylist(res)
{
    let container = $("#playlist-songs");
    let data = res.data.status;

    if (res.error != "no" || typeof data.queue.length === "undefined" || ! data.queue.length) {
        container.html("<div class='col-12'><em class='d-block m-auto'>No songs</em></div>");
        return;
    }

    let template = $('#playlist-song-template>div');
    container.html("");

    let indice = 0;
    for (song of data.queue)
    {
        let songTemplate = template.clone();
        let length = parseInt(song.length ?? 0);
        if (data.index == indice) {
            songTemplate.addClass(".active");
            songTemplate.find(data.playing ? ".icon-playing" : ".icon-paused").show();
        }
        songTemplate.find(".index").text(indice + 1);
        songTemplate.find(".name").text(song.name != "undefined" ? song.name : song.uri);
        songTemplate.find(".length").text((data.index == indice ? timeStrFromSecs(res.data.playingTime) + " / " : "") + timeStrFromSecs(song.length));
        container.append(songTemplate);

        indice++;
    }

    container[0].scrollTo(0, container.children(0).outerHeight() * (data.index + 1));

    enablePlaylistSynchronisation(data.playing);

}

function displayError(error)
{
    if (error != "no") alert("Error : " + error);
}

function displayDevices(devices)
{
    $("#bt-devices-refresh-icon").hide();
    let container = $("#bt-devices");

    if (typeof devices.length === "undefined" || ! devices.length) {
        container.html("<div class='col'>No devices</div>");
        return;
    }

    let template = $("#bt-device-template>div");
    container.html("");

    for (device of devices)
    {
        let deviceContainer = template.clone();
        deviceContainer.find(".info-mac").text(device.mac);
        if (device.classname)
            deviceContainer.find(".class-icons .class-" + device.classname).show();
        if (device.name)
            deviceContainer.find(".info-name").text(device.name);
        if (device.available)
            deviceContainer.find(".info-available").show();
        if (device.paired)
            deviceContainer.find(".info-paired").show();
        if (device.connected) {
            deviceContainer.find(".info-connected").show();
            deviceContainer.find(".disconnect-device").show().data("mac", device.mac).click(disconnectDevice);
        }
        if (device.rssi) {
            let RSSIMax = -40, RSSIMin = -100;
            let nbBars = parseInt((device.rssi - RSSIMin) / (RSSIMax - RSSIMin) * 5);
            nbBars = nbBars < 1 ? 1 : (nbBars > 5 ? 5 : nbBars);
            let width = nbBars * 20;

            deviceContainer
                .find(".info-rssi")
                .show()
                .prop("title", "RSSI : " + device.rssi + " dBm");

            deviceContainer
                .find(".signal-overlay")
                .css("width", width + "%");
        }

        if (device.available || device.paired || devices.connected || device.paired)
            deviceContainer.find('.icon-container').show();

        let blockButton = deviceContainer.find(".block-device");
        let hideIcon = blockButton.find(".unblock-icon");
        let showIcon = blockButton.find(".block-icon");

        if (device.blocked)
        {
            let tmp = hideIcon;
            hideIcon = showIcon;
            showIcon = tmp;
        }

        hideIcon.hide();
        showIcon.show();

        blockButton
            .addClass(device.blocked ? "btn-outline-success" : "btn-outline-danger")
            .removeClass(device.blocked ? "btn-outline-danger" : "btn-outline-success")
            .prop("title", (device.blocked ? "" : "Un") + "block device")
            .data("mac", device.mac)
            .data("blocked", device.blocked ? "yes" : "no")
            .click(blockDevice);
        container.append(deviceContainer);
    }
}
