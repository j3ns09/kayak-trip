let road = [];
let distances = [];

const stepDiv = `
    <div id="__id__" class="d-flex flex-column col align-items-center">
        <div class="step-label mb-2 text-white">__stop__</div>
        <div class="step __class__">__icon__</div>
        <div class="step-label text-white">__name__</div>
    </div>
`;

const icons = [
    '<i class="bi bi-flag"></i>',
    '<i class="bi bi-geo"></i>',
    '<i class="bi bi-geo-alt"></i>'
];

const stopContainer = document.getElementById('stop-container');
const totalDistance = document.getElementById('total-distance');
const checkboxesContainer = document.getElementById('route-form');

function stepGenerator(id, name, stop, status, iconIndex) {
    const icon = icons[iconIndex];
    let cls;

    switch (status) {
        case 'completed':
            break;
        case 'active':
            break;
        default:
            status = '';
            break;
    }

    cls = status;

    let step = stepDiv.replace('__stop__', stop).replace('__id__', id).replace('__class__', cls).replace('__icon__', icon).replace('__name__', name);
    return step;
}

export function loadRoadMap() {
    checkboxesContainer.addEventListener('change', (e) => {
        const checkbox = e.target;

        if (checkbox.type !== 'checkbox') return;
        
        const stopName = checkbox.dataset.name;
        const stopId = `step-${stopName}`;

        if (checkbox.checked) {
            let stop = {
                name: stopName,
                lat: checkbox.dataset.lat,
                lng: checkbox.dataset.lng
            };

            if (!road.some((step) => step.name === stopName)) {
                road.push(stop);
                let newStepIndex = road.length - 1;
                let newStep;
                if (newStepIndex === 0) {
                    newStep = stepGenerator(stopId, stopName, 'Départ', 'completed', 1);
                } else {
                    newStep = stepGenerator(stopId, stopName, road.length - 1, '', 1);
                }
                stopContainer.innerHTML += newStep;
            }

            if (road.length > 1) {
                let s1 = road[road.length - 1];
                let s2 = road[road.length - 2];

                let d = haversine(s1.lat, s1.lng, s2.lat, s2.lng);
                console.log(d);
                distances.push(d);
                totalDistance.innerHTML = distances.reduce((a, b) => a + b) + " km";
            }


        } else {
            road = road.filter(step => step.name !== stopName);

            const stepDiv = document.getElementById(`step-${stopName}`);
            if (stepDiv) stepDiv.remove();
        }
    });
}

function haversine(lat1, lon1, lat2, lon2) {
    const R = 6371;

    const toRadians = angle => angle * (Math.PI / 180);

    const phi1 = toRadians(lat1);
    const phi2 = toRadians(lat2);
    const dPhi = toRadians(lat2 - lat1);
    const dLambda = toRadians(lon2 - lon1);

    const a = Math.sin(dPhi / 2) ** 2 +
              Math.cos(phi1) * Math.cos(phi2) *
              Math.sin(dLambda / 2) ** 2;

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const distance = R * c;

    return distance;
}
