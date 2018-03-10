<script>

    function makePreviewOnlyGoogleMapWithMarker(mapID, latID, lonID){

        latID = {value: latID};
        lonID = {value: lonID};

        var map;
        var markers = [];

        var setExistingMarker = function(lat, lon) {

            clearMarkers();
            map.panTo({lat: lat, lng: lon});
            map.setZoom(15);
            putMarker( {location:{lat: lat, lng: lon}} );

        };

        var initMap = function(){
            // list of countries from google code example preset
            var countries = {
                'us': {
                    center: {lat: 37.1, lng: -95.7},
                    zoom: 3
                }
            };

            // map options
            map = new google.maps.Map(document.getElementById(mapID), {
                zoom: countries['us'].zoom,
                center: countries['us'].center,
                mapTypeControl: false,
                panControl: false,
                zoomControl: true,
                streetViewControl: false
            });

        };

        var putMarker = function (geometry) {
            // put marker on select results from autocomplete proposals
            markers.push(
                new google.maps.Marker({
                    draggable: false,
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

        };

        function clearMarkers() {
            // delete all markers
            for (var i = 0; i < markers.length; i++) {
                if (markers[i]) {
                    markers[i].setMap(null);
                }
            }

            markers = [];

        }

        {{--function eventMarkerPlaced(marker) {--}}
            {{--// event handler, fired on drop of manual marker dragging--}}
            {{--var geocoder = new google.maps.Geocoder();--}}

            {{--geocoder.geocode({ latLng: marker.getPosition() }, function(results, status){--}}

                {{--if (status == google.maps.GeocoderStatus.OK)--}}
                {{--{--}}

                    {{--document.getElementById('{{ $idAutocomplete }}').value = results[0].formatted_address;--}}

                    {{--placeID.value = results[0].place_id;--}}
                    {{--latID.value = results[0].geometry.location.lat();--}}
                    {{--lonID.value = results[0].geometry.location.lng();--}}

                {{--}--}}

            {{--});--}}

        {{--}--}}

        initMap();

        setExistingMarker(parseFloat(latID.value), parseFloat(lonID.value));
        document.getElementById(mapID)

    }


</script>