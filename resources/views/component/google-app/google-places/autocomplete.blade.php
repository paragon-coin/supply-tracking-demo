<script>

    var map;
    var markers = [];
    var autocomplete;
//    var MARKER_PATH = 'http://files.softicons.com/download/web-icons/vista-map-markers-icons-by-icons-land/png/64x64/MapMarker_Marker_Inside_Pink.png';

    var placeID = document.getElementById('{{ $idPlaceID }}');
    var latID = document.getElementById('{{ $idLat }}');
    var lonID = document.getElementById('{{ $idLon }}');

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */
            (document.getElementById('{{ $idAutocomplete }}')),
            {types: ['geocode']}
        );



        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', eventPlaceChanged);

        initMap();

        if(
            (typeof latID.value === typeof '' && latID.value.length)
            &&
            (typeof lonID.value === typeof '' && lonID.value.length)
        ){

            setExistingMarker(parseFloat(latID.value), parseFloat(lonID.value));

        }

    }

    function setExistingMarker(lat, lon) {

            clearMarkers();

            map.panTo({lat: lat, lng: lon});
            map.setZoom(15);
            putMarker( {location:{lat: lat, lng: lon}} );
            var gh= 1;
    }

    function eventPlaceChanged() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        placeID.value = place.place_id;
        latID.value = place.geometry.location.lat();
        lonID.value = place.geometry.location.lng();


        if (place.geometry) {
            clearMarkers();
            map.panTo(place.geometry.location);
            map.setZoom(15);
            putMarker( place.geometry );
        }

    }

    function initMap(){
        // list of countries from google code example preset
        var countries = {
            'us': {
                center: {lat: 37.1, lng: -95.7},
                zoom: 3
            }
        };

        // map options
        map = new google.maps.Map(document.getElementById('{{ $idMap }}'), {
            zoom: countries['us'].zoom,
            center: countries['us'].center,
            mapTypeControl: false,
            panControl: false,
            zoomControl: true,
            streetViewControl: false
        });

    }

    function putMarker(geometry) {
        // put marker on select results from autocomplete proposals
        markers.push(
            new google.maps.Marker({
                draggable: true,
                map: map,
                position: geometry.location,
                animation: google.maps.Animation.DROP
            })
        );

        var idx = markers.length - 1;

        // event on drop of manual marker dragging
        markers[idx].addListener('dragend', function(){
            eventMarkerPlaced(markers[idx]);
        });

    }

    function clearMarkers() {
        // delete all markers
        for (var i = 0; i < markers.length; i++) {
            if (markers[i]) {
                markers[i].setMap(null);
            }
        }

        markers = [];

    }

    function eventMarkerPlaced(marker) {
        // event handler, fired on drop of manual marker dragging
        var geocoder = new google.maps.Geocoder();

        geocoder.geocode({ latLng: marker.getPosition() }, function(results, status){

            if (status == google.maps.GeocoderStatus.OK)
            {

                document.getElementById('{{ $idAutocomplete }}').value = results[0].formatted_address;

                placeID.value = results[0].place_id;
                latID.value = results[0].geometry.location.lat();
                lonID.value = results[0].geometry.location.lng();

            }

        });

    }

</script>