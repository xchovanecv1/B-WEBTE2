/*
* Vytvorenie mapy
*/

var map;
var infowindow;
var ba;
var routeLength;
var data = { waypoints: null, start:null, end:null};
var wayA, wayB;
var gmarkers = [];
var flag;
var fromAddress=false;

function save_waypoints(){
    var length;
    var w=[],wp;
    var rleg = directionsDisplay.directions.routes[0].legs[0];

    data.start = {
        'lat': rleg.start_location.lat(),
        'lng':rleg.start_location.lng()
    }

    data.end = {
        'lat': rleg.end_location.lat(),
        'lng':rleg.end_location.lng()
    }

    var wp = rleg.via_waypoints
    for(var i=0;i<wp.length;i++){
        w[i] = [wp[i].lat(),wp[i].lng()]
    }
    data.waypoints = w;

    var str = JSON.stringify(data);
    console.log(JSON.parse(str));

    // aktualizujem dlzku trasy, ked sa zmeni tahanim
    var wp2 = [];
    for(var i=0;i<data.waypoints.length;i++){
        wp2[i] = {
            'location': new google.maps.LatLng(data.waypoints[i][0], data.waypoints[i][1]),
            'stopover':false
        }
    }

    length = drawWhenChanged(wp2);
    return {mapDefinition: str, routeLength:length};
}

function drawWhenChanged(wp2){
    directionsService.route({
            waypoints: wp2,
            origin: data.start,
            destination: data.end,
            travelMode: google.maps.TravelMode["WALKING"]
        },
        function(response, status) {
            routeLength = response.routes[0].legs[0].distance.value/1000;
            var length = response.routes[0].legs[0].distance.value/1000;
            if (status == google.maps.DirectionsStatus.OK) {
                routeLength = response.routes[0].legs[0].distance.value/1000;
            }
        });
    return length;
}

//inicializacia mapy pri zapnuti aplikacie
function initMap() {
    //mapa bude pri spusteni sustredena na tento bod
    ba = {lat: 48.148596, lng: 17.107748};
    map = new google.maps.Map(document.getElementById('map'), {
        center: ba,
        zoom: 14
    });

    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer({
        //povolime menenie trasy dragovanim keypointom
        draggable: true,
        map: map,
        //suppressMarkers: true,
        panel: document.getElementById('right-panel')
    });



    //pri zmene trasy tahanim, vytvaranim novych bodov aktualizujem premennu actualRoute (JSON do DB)
    google.maps.event.addListener(directionsDisplay, 'directions_changed',
        function() {
            removeMarkers();
            var newRoute = save_waypoints();
            setTimeout(function(){
                passVal(newRoute["mapDefinition"]);
            }, 500);

        });

    google.maps.event.addListener(map, "click", function(event) {
        if((!wayA || !wayB) && (fromAddress==false)){
            if (!wayA) {
                wayA = new google.maps.Marker({
                    position: event.latLng,
                    label: {
                        color: 'white',
                        text: 'A',
                    },
                    map: map
                });
                gmarkers.push(wayA);
            }
            else {
                wayB = new google.maps.Marker({
                    position: event.latLng,
                    label: {
                        color: 'white',
                        text: 'B',
                    },
                    map: map
                });
                gmarkers.push(wayB);

                directionsService.route({
                    'origin': wayA.getPosition(),
                    'destination': wayB.getPosition(),
                    'travelMode': google.maps.DirectionsTravelMode.WALKING
                }, function(res, sts) {
                    routeLength = res.routes[0].legs[0].distance.value/1000;
                    //console.log(routeLength);
                    if (sts == 'OK') directionsDisplay.setDirections(res);
                })

            }
        }
    });

    // Create the search box and link it to the UI element.
    var inputFrom = document.getElementById('address-from');
    var inputTo = document.getElementById('address-to');

    var searchBoxFrom = new google.maps.places.SearchBox(inputFrom);
    var searchBoxTo = new google.maps.places.SearchBox(inputTo);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBoxFrom.setBounds(map.getBounds());
    });

    map.addListener('bounds_changed', function() {
        searchBoxTo.setBounds(map.getBounds());
    });

    //infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    var geocoder = new google.maps.Geocoder();
    document.getElementById('showWay').addEventListener('click', function() {
        geocodeAddress(geocoder, map);
    });
}

function removeMarkers(){
    for(i=0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
    }
}

function passVal(rout_definition){
  
    var jsonData = {
        length: routeLength,
        definition: rout_definition
        };
        /*
    $.post("./gateway.php", data).done(function(data){
        console.log(data);
    });
    */
    console.log(jsonData);
    $("#mapData").val(JSON.stringify(jsonData));
    //document.cookie = "definition=" + rout_definition;

}

//geokodovanie vstupnej adresy z pola "od" a pola "do"
function geocodeAddress(geocoder, resultsMap) {
    var addressFrom = document.getElementById('address-from').value;
    var addressTo = document.getElementById('address-to').value;
    fromAddress = true;
    var okFrom; var resultFrom;
    var okTo; var resultTo;
    if(addressFrom.length > 0 && addressTo.length > 0)
    {
        geocoder.geocode({'address': addressFrom}, function(results, status) {
            if (status === 'OK') {
                resultFrom = results[0].geometry.location;

                geocoder.geocode({'address': addressTo}, function(results, status) {
                    if (status === 'OK') {
                        resultTo = results[0].geometry.location;
                        showRoute(resultFrom, resultTo);
                    } else {
                        console.log('Geocode (for address to) was not successful for the following reason: ' + status);
                    }
                });
            } else {
                console.log('Geocode (for address from) was not successful for the following reason: ' + status);
            }
        });
    }
}

function showRoute(locationFrom, locationTo){
    calculateAndDisplayRoute(locationFrom, locationTo, null);
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
            if (status == google.maps.DirectionsStatus.OK) {
                routeLength = response.routes[0].legs[0].distance.value/1000;
                directionsDisplay.setDirections(response);

                console.log(response.routes[0].legs[0].duration.value);
                console.log(response.routes[0].legs[0].distance.value);
                routeLength = response.routes[0].legs[0].distance.value/1000;
                passVal(save_waypoints());
            } else {
                console.log('Directions request failed due to ' + status);
            }
        });
}
