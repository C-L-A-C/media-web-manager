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
        if (device.name)
            deviceContainer.find(".info-name").text(device.name);
        if (device.available)
            deviceContainer.find(".info-available").show();
        if (device.paired)
            deviceContainer.find(".info-paired").show();
        if (device.connected)
            deviceContainer.find(".info-connected").show();
        if (device.rssi)
            deviceContainer.find(".info-rssi").show().prop("title", "RSSI : " + device.rssi + " dBm");

        if (device.available || device.paired || devices.connected || device.paired)
            deviceContainer.find('.icon-container').show();

        container.append(deviceContainer);
    }
}
