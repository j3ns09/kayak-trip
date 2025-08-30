let stopsData = [];
let currentPage = 1;
const itemsPerPage = 10;


export async function loadStops() {
    const response = await fetch("/api/stops/", { method: 'GET' });

    const data = await response.json();
    stopsData = data.stops;
    window.allStops = stopsData;

    renderPage(1);
    renderPagination();
}

function renderPage(page) {
    currentPage = page;
    const tbody = document.querySelector('#stopsShowing');
    const stopsSelect = document.getElementById("arret-list");

    tbody.innerHTML = "";
    stopsSelect.innerHTML = "";

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageStops = stopsData.slice(start, end);

    let no = start + 1;
    for (const stop of pageStops) {
        const id = stop.id;
        const name = stop.name;
        const lat = stop.latitude;
        const lng = stop.longitude;
        const type = stop.description;

        const option = document.createElement('option');
        option.value = id;
        option.innerHTML = `${name}`;
        stopsSelect.appendChild(option);

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no}</td>
            <td>${name}</td>
            <td>${lat}</td>
            <td>${lng}</td>
            <td>${type}</td>
            <td>
                <button class="btn btn-sm btn-info view-stop" data-lat="${lat}" data-lng="${lng}"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStop${id}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStop${id}"><i class="bi bi-trash"></i></button>

                ${generateDeleteModal(id)}
                ${generateEditModal(id, stop)}
            </td>`;
        tbody.appendChild(tr);

        const viewButton = tr.querySelector('.view-stop');
        viewButton.addEventListener('click', () => {
            document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'start' });
            const lat = parseFloat(viewButton.dataset.lat);
            const lng = parseFloat(viewButton.dataset.lng);
            if (!isNaN(lat) && !isNaN(lng)) {
                map.flyTo([lat, lng], 14, { animate: true, duration: 1 });
            }
        });

        no++;
    }
}

function renderPagination() {
    const totalPages = Math.ceil(stopsData.length / itemsPerPage);
    const pagination = document.getElementById("step-pagination");
    pagination.innerHTML = "";

    const prevLi = document.createElement("li");
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<button class="page-link" aria-label="Previous"><i class="bi bi-arrow-left-circle"></i></button>`;
    prevLi.addEventListener("click", () => {
        if (currentPage > 1) {
            renderPage(currentPage - 1);
            renderPagination();
        }
    });
    pagination.appendChild(prevLi);

    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement("li");
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<button class="page-link">${i}</button>`;
        li.addEventListener("click", () => {
            renderPage(i);
            renderPagination();
        });
        pagination.appendChild(li);
    }

    const nextLi = document.createElement("li");
    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<button class="page-link" aria-label="Next"><i class="bi bi-arrow-right-circle"></i></button>`;
    nextLi.addEventListener("click", () => {
        if (currentPage < totalPages) {
            renderPage(currentPage + 1);
            renderPagination();
        }
    });
    pagination.appendChild(nextLi);
}

function generateDeleteModal(id) {
    return `
    <div class="modal fade" id="deleteStop${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/stops/delete_stop_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer un point d'arrêt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce point d'arrêt ?
                <input type="hidden" name="id" value="${id}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-success">Supprimer</button>
            </div>
            </form>
        </div>
        </div>
    </div>`;
}

function generateEditModal(id, stop) {
    const desc = stop.description;

    const descriptions = {
        'embarquement' : "Point d'embarquement",
        'etape' : "Aire de repos / étape",
        'abri' : "Abri",
        'ravitaillement' : "Point de ravitaillement",
        'autre' : "Autre"
    };
    
    let r = `
    <div class="modal fade" id="editStop${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/stops/edit_stop_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Modifier un point d'arrêt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="${id}">
                <div class="mb-3"><label>Nom</label><input name="nom" class="form-control" value="${stop.name}" /></div>
                <div class="mb-3"><label>Latitude</label><input name="latitude" class="form-control" value="${stop.latitude}" /></div>
                <div class="mb-3"><label>Longitude</label><input name="longitude" class="form-control" value="${stop.longitude}" /></div>
                <div class="mb-3">
                    <label for="type_hebergement" class="form-label">Type</label>
                    <select name="description" id="description" class="form-select">
                        <option value="${stop.description}" selected>${stop.description}</option>`;
    
                        Object.entries(descriptions).forEach(([value, label]) => {
                            if (value !== desc) {
                                r += `<option value="${value}">${label}</option>`;
                            }
                        });
    r += `</select>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-success">Sauvegarder</button>
            </div>
            </form>
        </div>
        </div>
    </div>`;

    return r;
}