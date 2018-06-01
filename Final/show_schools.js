/*
* Uvodna stranka - markre na skoly bezcov
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

    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer({
        //povolime menenie trasy dragovanim keypointom
        map: map
    });

    infowindow = new google.maps.InfoWindow();

    //infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    geocoder = new google.maps.Geocoder();

    test_residence_API();

}

function geocodeAddress(school) {
    console.log(school);
    addRunnerPositionMarker(school.geo, school);
}

function showRoute(location){
    calculateAndDisplayRoute(sk, location);
}


function test_residence_API() {
    let data = {
        "action": "schoolMap"
    };

    $.post("./gateway.php", data).done(function (data) {
        let response = jQuery.parseJSON(data);
        console.log(response);
        for(var i = 0; i<response["schools"].length; i++) {
            var dt = response["schools"][i];
            var jsn = JSON.parse(dt['geo']);
            dt['geo'] = jsn;
            geocodeAddress(response["schools"][i]);
        }
    });
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

//https://stackoverflow.com/questions/10976425/google-maps-directions-api-marking-a-point-x-km-from-starting-point
function getLatLngOnRoute(directionResult, distanceInKM) {
    console.log(directionResult);
    console.log(distanceInKM);
    var lastPos=directionResult.routes[0].legs[0].steps[0].path[0]; var currPos;
    var distance=0.0;
    distanceInKM*=1000;
    console.log(directionResult.routes[0].legs.length);
    //Will not consider alternate routes.
    for (var j=0; j<directionResult.routes[0].legs.length; j++) {
        //There may be multiple legs, each corresponding to one way point. If there are no way points specified, there will be a single leg
        for (var k=0; k<directionResult.routes[0].legs[j].steps.length; k++) {
            //There will be multiple sub legs or steps
            for (var l=0; l<directionResult.routes[0].legs[j].steps[k].path.length; l++) {
                currPos=directionResult.routes[0].legs[j].steps[k].path[l];
                //Calculate the distance between two lat lng sets.
                distance+=google.maps.geometry.spherical.computeDistanceBetween(lastPos, currPos);
                if (distance>distanceInKM) {
                    //If the exact point comes between two points, use distance to lat-lng conversion
                    var heading=google.maps.geometry.spherical.computeHeading(currPos, lastPos);
                    var l= google.maps.geometry.spherical.computeOffset(currPos, distance-distanceInKM, heading);
                    return l;

                }
                lastPos=currPos;
            }
        }
    }
}

function addRunnerPositionMarker(location, user) {

    var residents = "";
    for(var i=0; i<user.users.length;i++){
        if(i != user.users.length-1){
            residents += user.users[i] + ", ";
        }
        else{
            residents += user.users[i];
        }
    }

    var contentString =
        "<h5>Škola: </h5>" + user.name+
        "<h5>Bežci: </h5>" + residents;


    var marker = new google.maps.Marker({
        position: location,
        name: contentString,
        icon: {
            url: "https://mt.google.com/vt/icon/name=icons/spotlight/university_L_8x.png&scale=1.15",
            origin: new google.maps.Point(0, 0),
        },
        map: map
    });

    fei_marker = marker;

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(marker.name);
        infowindow.open(map, this);
    });
}