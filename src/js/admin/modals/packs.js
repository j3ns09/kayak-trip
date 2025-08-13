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

function generateEditModal(id, pack) {
    return `
    <div class="modal fade" id="editPack${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/packs/edit_pack_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Modifier un pack</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="${id}">
                <div class="mb-3"><label class="form-label">Nom</label><input name="name" class="form-control" value="${pack.name}" /></div>
                <div class="mb-3"><label class="form-label">Durée</label><input name="duration" class="form-control" value="${pack.duration}" /></div>
                <div class="mb-3"><label class="form-label">Description</label><input name="description" class="form-control" value="${pack.description}" /></div>
                <div class="mb-3"><label class="form-label">Prix</label><input name="price" class="form-control" value="${pack.price}" /></div>
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
