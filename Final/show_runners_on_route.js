/*
* Vykreslenie pozicie vsetkych bezcov na trati
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

    infowindow = new google.maps.InfoWindow();

    test_route_API();

    //zobraz aktualnu trasu

    //infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    var geocoder = new google.maps.Geocoder();

}


function showRouteRunners(inp, users){

    console.log(inp);
    
    var os = JSON.parse(inp['definition']);

    var pointA = new google.maps.LatLng(os.start.lat,os.start.lng);
    var pointB = new google.maps.LatLng(os.end.lat,os.end.lng);
    var wp = [];
    for(var i=0;i<os.waypoints.length;i++){
        wp[i] = {
            'location': new google.maps.LatLng(os.waypoints[i][0], os.waypoints[i][1]),
            'stopover':false
        }
    }

    // vypocita a zobrazi mapu z DB
    calculateAndDisplayRoute(pointA, pointB, wp);



    var selectedMode = "WALKING";
    directionsService.route({
            waypoints: wp,
            origin: pointA,
            destination: pointB,
            travelMode: google.maps.TravelMode[selectedMode]
        },

        function(response, status) {
            console.log("vykreslujem poziciu bezca");


            for (var i = 0; i < users.length; i++) {
                var location = getLatLngOnRoute(response, users[i].length);
                addRunnerPositionMarker(location, map, users[i], i);
            }

            //vykresli poziciu bezca na trase
            //addRunnerPositionMarker(location, map);
        });
}


function test_route_API() {
    let data = {
        "action": "getRunningData",
        "routeID": document.getElementById('routeID').value
    };

    $.post("./gateway.php", data).done(function (data) {

        let response = jQuery.parseJSON(data);
        console.log(response);
        showRouteRunners(JSON.parse(response["description"]), response["users"]);
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

function addRunnerPositionMarker(location, map, user, index) {
    var order= (index + 1);

    if(user.type == "user"){
        var contentString =
            "<h5>Meno bežca: </h5>" + user.name+
            "<h5>Prejdená vzdialenosť: </h5>" + user.length;

    }
    if(user.type == "group"){
        var residents = "";
        for(var i=0; i<user.members.length;i++){
            if(i != user.members.length-1){
                residents += user.members[i] + ", ";
            }
            else{
                residents += user.members[i];
            }
        }

        var contentString =
            "<h5>Názov tímu: </h5>" + user.team+
            "<h5>Členovania tímu: </h5>" + residents+
            "<h5>Prejdená vzdialenosť: </h5>" + user.length;
    }

    var marker = new google.maps.Marker({
        position: location,
        name: contentString,
        icon: {
            url: "https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_green" + order + ".png",
        },
        map: map
    });


    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(marker.name);
        infowindow.open(map, this);
    });
}