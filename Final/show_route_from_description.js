
/*
* Vykreslenie polohy bezca v konkretnej trase
*/

var map;
var sk;
var infowindow;


//inicializacia mapy pri zapnuti aplikacie
function initMap() {
    //mapa bude pri spusteni sustredena na tento bod
    sk = {lat: 48.669026, lng: 19.699024};
    map = new google.maps.Map(document.getElementById('map'), {
        center: sk,
        zoom: 7
    });

    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer({
        //povolime menenie trasy dragovanim keypointom
        map: map
    });

    directions = directionsDisplay.getDirections();
    infowindow = new google.maps.InfoWindow();

    var description = document.getElementById('mapData').value;
    description = window.atob(description);
    var pars = JSON.parse(description);
    var def = JSON.parse(pars['definition']);

    showRoute(def);

    //infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    var geocoder = new google.maps.Geocoder();

}

function showRoute(os){
    var pointA = new google.maps.LatLng(os.start.lat,os.start.lng);
    var pointB = new google.maps.LatLng(os.end.lat,os.end.lng);
    var wp = [];
    for(var i=0;i<os.waypoints.length;i++){
        wp[i] = {
            'location': new google.maps.LatLng(os.waypoints[i][0], os.waypoints[i][1]),
            'stopover':false
        }
    }
    // vypocita a zobrazi mapu
    calculateAndDisplayRoute(pointA, pointB, wp);
}


function calculateAndDisplayRoute(pointA, pointB, wp) {
    var selectedMode = "WALKING";
    directionsService.route({
            waypoints: wp,
            origin: pointA,
            destination: pointB,
            travelMode: google.maps.TravelMode[selectedMode]
        },

        function(response, status) {
            console.log(response);
            directions = response;
            console.log(directions);
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                console.log(response.routes[0].legs[0].duration.value);
                console.log(response.routes[0].legs[0].distance.value);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
}