var syncRoutineID = null;

$(document).ready(function(){

    enableSyncronisation();

    $("#sync-button").click(function(event) {
        let container = $(event.delegateTarget);
        container.find(".icon-container").toggle();
        let shouldEnable = syncRoutineID === null;
        enableSyncronisation(shouldEnable);
    });

    $("#bt-status-button").click(function(event) {
        let container = $(event.delegateTarget);
        let action = container.find(".btn:visible").data('action');
        container.find(".btn").toggle();
        fetch(action, {method: "POST"});
    });

});

function enableSyncronisation(enable = true)
{
    if (enable)
        syncRoutineID = setInterval(syncroniseData, 5000);
    else if (syncRoutineID) {
        clearInterval(syncRoutineID);
        syncRoutineID = null;
    }
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

function disconnectDevice(e)
{
    doApiCall("disconnect", e);
}

function blockDevice(e)
{
    doApiCall($(e.delegateTarget).data('blocked') === "yes" ? "unblock" : "block", e);
}

function doApiCall(action, event)
{
    let mac = $(event.delegateTarget).data("mac");
    let route = $("#deviceOperationAPI").data("route");

    fetch(route, {
        method: "POST",
        body: new URLSearchParams({
            action:action,
            mac: mac
        })
    })
    .then(data => data.json())
    .then(status => {
        refreshDevicesList();
        displayError(status.error);
    })
    .catch(error => displayError("server"));
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
