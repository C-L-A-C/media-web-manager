$(document).ready(function(){
    $("#bt-devices-refresh").click(function(event) {
        let route = $(event.target).data('route');
        fetch(route)
            .then(data => data.json())
            .then(json => console.log(json))
            .catch(e => console.error(e));
    });

});
