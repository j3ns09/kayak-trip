let clickedMarker = null;

export function loadMap() {
    map = L.map('map').setView([47.3, 0.7], 7);
    
    map.setMaxBounds([
        [43.8, -3.0],
        [49.5, 5.0]
    ]);
    
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap – Leaflet',
        minZoom: 6,
        maxZoom: 18
    }).addTo(map);

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
    

    fetch("/api/stops/", {
        method: "GET"
    }).then(response => {
        if (!response.ok) throw new Error("Erreur lors de la requête à la base de données");
        return response.json();
    }).then(data => {
        const stops = data.stops;
        stops.forEach(stop => {
            L.circleMarker([stop.latitude, stop.longitude], {
                radius: 6,
                fillColor: getColor(stop.description),
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);
        });
    }).catch(error => {
        console.error("Erreur :", error);
    })  
    
    map.on('click', onMapClick);
}


function onMapClick(e) {
    const latInput = document.getElementById("latitude");
    const lngInput = document.getElementById("longitude");
    
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    if (clickedMarker !== null) {
        map.removeLayer(clickedMarker);
    }
    
    clickedMarker = L.marker([lat, lng]).addTo(map);
    latInput.value = lat;
    lngInput.value = lng;
}

function getColor(type) {
    switch (type) {
        case 'etape':
            return 'blue';
        case 'embarquement':
            return 'orange';
        default:
            return 'blue';
    }
}