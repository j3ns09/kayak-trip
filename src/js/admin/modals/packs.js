export async function loadPacks() {
    const response = await fetch("/api/packs/", {
            method: 'GET'
    });

    const tbody = document.querySelector('#packsShowing');

    const data = await response.json();
    const packs = data.packs;
    const userIdSession = data.waiter;
    let no = 1;

    for (const pack of packs) {
        const id = pack.id
        const name = pack.name;
        const description = pack.description;
        const duration = pack.duration;
        const price = pack.price;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no}</td>
            <td>${name}</td>
            <td>${description}</td>
            <td>${duration} jours</td>
            <td>${price} €/pers.</td>
            <td>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPack${id}"><i class="bi bi-pencil"></i></button>
                
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deletePack${id}">
                    <i class="bi bi-trash3-fill"></i>
                </button>

                ${generateDeleteModal(id)}
                ${generateEditModal(id, pack)}
                
                </td>`;
        
        tbody.appendChild(tr);
        addStopRow(id);
        attachAddStopButtonListener(id);
        no++;
    }
}

function generateDeleteModal(id) {
    return `
    <div class="modal fade" id="deletePack${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/packs/delete_pack_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer un pack</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce pack ?
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

function attachAccommodationLoader(stopSelect, accommodationSelect) {
    stopSelect.addEventListener("change", async () => {
        const stopId = stopSelect.value;
        if (!stopId) {
            accommodationSelect.innerHTML = '<option value="">Choisissez un hébergement</option>';
            return;
        }

        try {
            const response = await fetch(`/api/accommodations/?stop_id=${stopId}`);
            if (!response.ok) throw new Error("Erreur API");

            let accommodations = await response.json();

            accommodationSelect.innerHTML = '<option value="">Choisissez un hébergement</option>';

            console.log(accommodations);
            accommodations = accommodations.accommodations;
            accommodations.forEach(acc => {
                const opt = document.createElement("option");
                opt.value = acc.id;
                opt.textContent = acc.name;
                accommodationSelect.appendChild(opt);
            });
        } catch (err) {
            console.error("Erreur chargement hébergements:", err);
            accommodationSelect.innerHTML = '<option value="">(Erreur de chargement)</option>';
        }
    });
}

function attachAddStopButtonListener(packId) {
    const btn = document.getElementById(`addStopBtn${packId}`);
    if (btn) {
        btn.addEventListener("click", () => {
            addStopRow(packId);
        });
    } else {
        console.warn(`Bouton addStopBtn${packId} introuvable`);
    }
}


function addStopRow(packId) {
    const container = document.getElementById(`editStopsContainer${packId}`);

    const row = document.createElement("div");
    row.classList.add("row", "mb-2");

    const stopDiv = document.createElement("div");
    stopDiv.classList.add("col-sm-6");
    const stopLabel = document.createElement("label");
    stopLabel.classList.add("form-label");
    stopLabel.textContent = "Arrêt";
    const stopSelect = document.createElement("select");
    stopSelect.name = "stop_id[]";
    stopSelect.classList.add("form-control", "selects-stops");

    stopSelect.innerHTML = window.allStops
        .map(s => `<option value="${s.id}">${s.name}</option>`)
        .join("");

    stopDiv.appendChild(stopLabel);
    stopDiv.appendChild(stopSelect);

    const accDiv = document.createElement("div");
    accDiv.classList.add("col-sm-6");
    const accLabel = document.createElement("label");
    accLabel.classList.add("form-label");
    accLabel.textContent = "Hébergement";
    const accSelect = document.createElement("select");
    accSelect.name = "accommodation_id[]";
    accSelect.classList.add("form-control", "selects-accommodations");
    accSelect.innerHTML = `<option value="">Choisissez un hébergement</option>`;
    accDiv.appendChild(accLabel);
    accDiv.appendChild(accSelect);

    row.appendChild(stopDiv);
    row.appendChild(accDiv);
    container.appendChild(row);

    attachAccommodationLoader(stopSelect, accSelect);
}

function generateEditModal(id, pack) {
    let stopsHtml = "";
    const servicesHtml = window.allServices.map(s => `
    <option value="${s.id}" ${pack.services.some(ps => ps.id === s.id) ? 'selected' : ''}>${s.name}</option>`).join("");


    if (pack.stops && pack.stops.length > 0) {
        pack.stops.forEach((stop, idx) => {
            stopsHtml += `
            <div class="row mb-2">
                <div class="col-sm-6">
                    <label class="form-label">Arrêt ${idx + 1}</label>
                    <select name="stop_id[]" class="form-control selects-stops" data-selected="${stop.id}">
                        ${window.allStops.map(s => `
                            <option value="${s.id}" ${s.id == stop.id ? "selected" : ""}>${s.name}</option>
                        `).join("")}
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Hébergement</label>
                    <select name="accommodation_id[]" class="form-control selects-accommodations" data-selected="${stop.accommodation_id}">
                        <option value="">Choisissez un hébergement</option>
                        ${stop.accommodations.map(acc => `
                            <option value="${acc.id}" ${acc.id == stop.accommodation_id ? "selected" : ""}>${acc.name}</option>
                        `).join("")}
                    </select>
                </div>
            </div>`;
        });

        console.log(stopsHtml);
    }

    return `
    <div class="modal fade" id="editPack${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="/processes/packs/edit_pack_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Modifier un pack</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="${id}">
                
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input name="name" class="form-control" value="${pack.name}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Durée</label>
                    <input name="duration" type="number" min="1" class="form-control" value="${pack.duration}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input name="description" class="form-control" value="${pack.description}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Prix</label>
                    <input name="price" type="number" step="0.01" class="form-control" value="${pack.price}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de participants</label>
                    <input name="person_count" type="number" class="form-control" value="${pack.person_count}" />
                </div>

                <div class="mb-3">
                    <label class="form-label">Services associés</label>
                    <select name="service_id[]" class="form-control" multiple>
                        ${servicesHtml}
                    </select>
                    <small class="text-muted">Maintenez Ctrl pour en sélectionner plusieurs</small>
                </div>

                <h6 class="mt-3">Étapes du pack</h6>
                <div id="editStopsContainer${id}">
                    ${stopsHtml}
                </div>
                <button id="addStopBtn${id}" type="button" class="btn btn-sm btn-primary">+ Ajouter un arrêt</button>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-success">Sauvegarder</button>
            </div>
            </form>
        </div>
        </div>
    </div>`;
}
