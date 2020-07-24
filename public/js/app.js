$(document).ready(function(){
    $("#bt-devices-refresh").click(function(event) {
        let route = $(event.target).data('route');
        $("#bt-devices-refresh-icon").show();

        fetch(route)
            .then(data => data.json())
            .then(displayDevices)
            .catch(e => console.error(e));
    });

    $(".ajax-trigger").click(function(event) {
        let target = $(event.delegateTarget);
        let action = target.data('action');
        console.log(target, action);
        fetch(action, {method: "POST"});
    });

});

function displayDevices(devices)
{
    $("#bt-devices-refresh-icon").hide();
    let container = $("#bt-devices");
    let text = "";
    for (device of devices)
    {
         text += device.mac;
        if (device.name)
            text += " (" + device.name + ")";
        if (device.available)
            text += ", in range";
        if (device.paired)
            text += ", paired";
        if (device.connected)
            text += ", connected";
        if (device.rssi)
            text += ", RSSI : " + device.rssi + " dBm";
        text += "\n";
    }
    container.val(text);
}
