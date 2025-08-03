export async function loadAccommodations() {
    const response = await fetch("/api/accommodations/", {
            method: 'GET'
    });

    const tbody = document.querySelector('#accommodationsShowing');

    const data = await response.json();
    const accommodations = data.stops;
    const userIdSession = data.waiter;
    let no = 1;

    for (const accommodation of accommodations) {
        const id = accommodation.id
        const name = accommodation.name;
        const lat = accommodation.latitude;
        const lng = accommodation.longitude;
        const type = accommodation.description;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no}</td>
            <td>${name}</td>
            <td>${lat}</td>
            <td>${lng}</td>
            <td>${type}</td>
            <td>
                <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStop${id}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStop${id}"><i class="bi bi-trash"></i></button>

                ${generateDeleteModal(id)}
                ${generateEditModal(id, accommodation)}
            </td>`;
                
        tbody.appendChild(tr);
        no++;
    }
}

function generateDeleteModal(id) {
    return `
    <div class="modal fade" id="deleteStop${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
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
    return `
    <div class="modal fade" id="editStop${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
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
                        <option value="" disabled selected>${stop.description}</option>
                        <option value="embarquement">Point d'embarquement</option>
                        <option value="etape">Aire de repos / étape</option>
                        <option value="abri">Abri</option>
                        <option value="ravitaillement">Point de ravitaillement</option>
                        <option value="autre">Autre</option>
                    </select>
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