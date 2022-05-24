//Recupérer la latitude et la longitude du html
const lat = document.querySelector('#lat').innerText;
const long = document.querySelector('#long').innerText;

//Affichage de la carte
let map = L.map('map').setView([lat, long], 13);

L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

//Popup qui indique la position de départ sur la carte.
let popup = L.popup()
    .setLatLng([lat, long])
    .setContent("Ici!")
    .openOn(map);

//Popup qui indique la latitude et la longitude quand on clique sur la carte
function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("Latitude : " + e.latlng.lat.toString() + ", longitude : "+ e.latlng.lng.toString())
        .openOn(map);
};

map.on('click', onMapClick);

//DL la carte en pdf
document.querySelector('#printMap').addEventListener('click', () => {
    window.print();
});
