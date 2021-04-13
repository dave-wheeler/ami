<div class="text-lg dark:text-gray-400"><p class="-mt-px">Center the map marker on your location:</p></div>
<div id="map"></div>
<br/>
<hr/>

<script>
    var map = L.map('map').fitWorld();
    var marker = null;

    function updateHidden(latlng) {
        $('input[name="lat"]').val(latlng.lat);
        $('input[name="lon"]').val(latlng.lng);
    }

    map.on('locationfound', function (e) {
        marker = L.marker(e.latlng);
        marker.addTo(map);
        updateHidden(e.latlng);
    });

    map.on('locationerror', function (e) {
        map.flyTo({lat: 0, lon: 0}, 1);
        marker = L.marker({lat: 0, lon: 0});
        marker.addTo(map);
    });

    map.on('click', function (e) {
        map.panTo(e.latlng, {animate: true, duration: .50});
        marker.setLatLng(e.latlng);
        updateHidden(e.latlng);
    });

    map.on('moveend', function (e) {
        if (marker === null) return;
        latlng = map.getCenter();
        marker.setLatLng(latlng);
        updateHidden(latlng);
    });

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: '{{ config('services.mapbox.token') }}'
    }).addTo(map);

    map.locate({setView: true, maxZoom: 16});
</script>
