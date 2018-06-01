/*
* Uvodna stranka - markre na bydlisko bezcov
*/

var map;
var sk;
var infowindow;
var geocoder;
var residence_location;

//inicializacia mapy pri zapnuti aplikacie
function initMap() {
    //mapa bude pri spusteni sustredena na tento bod
    sk = {lat: 48.669026, lng: 19.699024};
    map = new google.maps.Map(document.getElementById('map'), {
        center: sk,
        zoom: 7
    });

    google.maps.event.addListener(map, 'bounds_changed', function() {
        google.maps.event.addListener(map, 'click', function(args) {
            document.getElementById('lat').value = args.latLng.lat();
            document.getElementById('long').value = args.latLng.lng();
            //console.log('latlng', args.latLng);
        });
    });

}

