export function loadMap(lat=47.23985, lon=-1.461364) {
    var map = L.map('map').setView([lat, lon], 13);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    L.marker([lat, lon]).addTo(map);

    var loireStyle = {
        color: "#00BFFF",
        weight: 4,
        opacity: 0.8
    };

    fetch("/src/js/admin/map/export_loire.geojson")
        .then(response => {
            if (!response.ok) throw new Error("Erreur lors du chargement du GeoJSON");
            return response.json();
        })
        .then(data => {
            L.geoJSON(data, { style: loireStyle }).addTo(map);
        })
        .catch(error => {
            console.error("Erreur :", error);
        });
    
    map.on('click', onMapClick);
}


function onMapClick(e) {
    console.log("You clicked the map at " + e.latlng);
}


