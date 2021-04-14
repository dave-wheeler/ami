import $ from 'jquery';

window.$ = window.jQuery = $;

import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

import markerIcon from 'leaflet/dist/images/marker-icon.png';
import marker2xIcon from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

L.Marker.prototype.options.icon = L.icon({
    iconUrl: markerIcon,
    iconRetinaUrl: marker2xIcon,
    shadowUrl: markerShadow
});

const map = L.map('map').fitWorld();
let marker = null;

function updateHidden(latlng) {
    $('input[name="lat"]').val(latlng.lat);
    $('input[name="lon"]').val(latlng.lng);
}

map.on('locationfound', function (e) {
    marker = L.marker(e.latlng);
    marker.addTo(map);
    updateHidden(e.latlng);
});

map.on('locationerror', function () {
    map.flyTo({lat: 0, lon: 0}, 1);
    marker = L.marker({lat: 0, lon: 0});
    marker.addTo(map);
});

map.on('click', function (e) {
    map.panTo(e.latlng, {animate: true, duration: .50});
    marker.setLatLng(e.latlng);
    updateHidden(e.latlng);
});

map.on('moveend', function () {
    if (marker === null) return;
    let latlng = map.getCenter();
    marker.setLatLng(latlng);
    updateHidden(latlng);
});

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: mapboxAccessToken
}).addTo(map);

map.locate({setView: true, maxZoom: 16});
