export async function loadServices() {
    const response = await fetch("/api/services/", {
            method: 'GET'
    });
    
    const tbody = document.querySelector('#servicesShowing');

    const data = await response.json();
    const services = data.services;
    window.allServices = services;
    const userIdSession = data.waiter;
    let no = 1;

    for (const service of services) {
        const id = service.id
        const name = service.name;
        const description = service.description;
        const price = service.price;
        const isActive = service.is_active;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no}</td>
            <td>${name}</td>
            <td>${description}</td>
            <td>${price} €</td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editService${id}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteService${id}"><i class="bi bi-trash"></i></button>

                ${generateDeleteModal(id)}
                ${generateEditModal(id, service)}

            </td>`;

        tbody.appendChild(tr);

        no++;
    }
}

function generateDeleteModal(id) {
    return `
    <div class="modal fade" id="deleteService${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/services/delete_service_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer un service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce service ?
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

function generateEditModal(id, service) {
    return `
    <div class="modal fade" id="editService${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/services/edit_service_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Modifier un service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="${id}">
                <div class="mb-3"><label>Nom</label><input name="name" class="form-control" value="${service.name}" /></div>
                <div class="mb-3"><label>Description</label><input name="description" class="form-control" value="${service.description}" /></div>
                <div class="mb-3"><label>Prix</label><input name="price" class="form-control" value="${service.price}" /></div>
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