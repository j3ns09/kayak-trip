let accommodationsData = [];
let currentPage = 1;
const itemsPerPage = 10;

export async function loadAccommodations() {
    const response = await fetch("/api/accommodations/", {
        method: 'GET'
    });

    const data = await response.json();
    accommodationsData = data.accommodations;

    renderPage(1);
    renderPagination();
}


function renderPage(page) {
    currentPage = page;
    const tbody = document.querySelector('#accommodationsShowing');

    tbody.innerHTML = "";

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const accommodations = accommodationsData.slice(start, end);

    let no = start + 1;

    for (const accom of accommodations) {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${no}</td>
            <td>${accom.name}</td>
            <td>${accom.nb_chambres} chambres</td>
            <td>${accom.stop_name}</td>
            <td>${accom.description}</td>
            <td>${accom.base_price_per_night} €/nuit</td>
            <td>
                ${accom.dates_fermeture ? `Fermé : ${accom.dates_fermeture}` : '<span class="text-success">Ouvert</span>'}
            </td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAccom${accom.id}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccom${accom.id}"><i class="bi bi-trash"></i></button>

                ${generateDeleteModal(accom.id)}
                ${generateEditModal(accom)}
            </td>
        `;

        tbody.appendChild(tr);
        no++;
    }
}

function renderPagination() {
    const totalPages = Math.ceil(accommodationsData.length / itemsPerPage);
    const pagination = document.getElementById("accommodations-pagination");
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
    <div class="modal fade" id="deleteAccom${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="/processes/accommodations/delete_accommodation_process.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Supprimer un hébergement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de vouloir supprimer cet hébergement ?
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

function generateEditModal(accom) {
    return `
    <div class="modal fade" id="editAccom${accom.id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="/processes/accommodations/edit_accommodation_process.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier un hébergement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="${accom.id}">
                        <div class="mb-3">
                            <label>Nom</label>
                            <input name="name" class="form-control" value="${accom.name}" />
                        </div>
                        <div class="mb-3">
                            <label>Type</label>
                            <select name="type" class="form-select">
                                ${generateTypeOptions(accom.description)}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Prix de base (€)</label>
                            <input name="base_price" class="form-control" value="${accom.base_price_per_night}" type="number" step="0.01" />
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
}

function generateTypeOptions(selectedType) {
    const types = ['camping', 'gite', 'hotel', 'chambre_hote', 'refuge', 'autre'];
    return types.map(type => `<option value="${type}" ${type === selectedType ? 'selected' : ''}>${capitalize(type)}</option>`).join('');
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).replace('_', ' ');
}
