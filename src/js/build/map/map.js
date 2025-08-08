let loireCoords = [];
let pointsToLoire = {};
const stopMarkers = {};

export function loadMap() {
    map = L.map('map').setView([46.3, 4], 7);
    
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

    const unselectedPointStyle = {
        radius: 6,
        fillColor: 'blue',
        color: "#000",
        weight: 1,
        opacity: 1,
        fillOpacity: 0.8
    }

    fetch("/src/js/build/map/export_loire.geojson")
        .then(response => {
            if (!response.ok) throw new Error("Erreur lors du chargement du GeoJSON");
            return response.json();
        })
        .then(data => {
            L.geoJSON(data, {
                style: loireStyle,
                onEachFeature: function (feature) {
                    if (feature.geometry.type === "MultiLineString") {
                        loireCoords = feature.geometry.coordinates.map(coord => [coord[1], coord[0]]); // Leaflet = [lat, lng]
                    }
                }
            }).addTo(map);

            console.log(loireCoords);
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
            const marker = L.circleMarker([stop.latitude, stop.longitude], unselectedPointStyle).addTo(map);

            stopMarkers[stop.id] = marker;
            
        });
    }).catch(error => {
        console.error("Erreur :", error);
    });

    const checkboxes = document.querySelectorAll('#route-form input[name="stops[]"]');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const stopId = checkbox.value;
            const isChecked = checkbox.checked;

            const marker = stopMarkers[stopId];
            if (marker) {
                if (isChecked) {
                    marker.setStyle({
                        fillColor: 'red',
                        radius: 10
                    });
                } else {
                    marker.setStyle(unselectedPointStyle);
                }
            }
        });
    });

}