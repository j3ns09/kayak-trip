const serviceDiv = document.getElementById('services-region');

const buttonTemplate = `
<div class="col d-flex align-items-center gap-2">
    <div id="chk-service-__id__" class="checkbox-wrapper-63">
        <span for="chk-service-__id__" class="form-label fw-bold text-white pe-3">__name__ :</span>
        <label class="switch">
        <input type="checkbox" id="__id__" >
        <span class="slider"></span>
        </label>
    </div>
</div>
`;

export async function renderServices() {
    const services = await getServices();

    for (const service of services) {
        let wrapper = document.createElement('div');
        
        wrapper.innerHTML = makeButton(service);

        serviceDiv.appendChild(wrapper);
    }
}

function makeButton(service) {
    let button = buttonTemplate.replace('__name__', service.name).replaceAll('__id__', service.id);
    return button;
}

async function getServices() {
    const response = await fetch('/api/services/', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    });

    const data = await response.json();
    if (data.ok) {
        return data.services;
    } else {
        alert("Erreur lors de la récupération des services");
    }
}