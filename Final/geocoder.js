var geocoder;

function initMap(){
    document.getElementById('geocode').addEventListener('click', function() {
        var inputAddress = document.getElementById('address').value;
        geocoder = new google.maps.Geocoder();
        geocodeAddress(inputAddress);
    });
}

function geocodeAddress(address) {
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            var GeoLat = results[0].geometry.location.lat();
            var GeoLng = results[0].geometry.location.lng();
            add_location = {lat: GeoLat, lng: GeoLng};
            console.log(add_location);
            document.getElementById('result').value = add_location.lat + " " + add_location.lng;
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}








