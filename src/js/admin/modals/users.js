export async function loadUsers() {
    const response = await fetch("/api/users/", {
            method: 'GET'
    });

    const tbody = document.querySelector('#usersShowing');

    const data = await response.json();
    const users = data.users;
    const userIdSession = data.waiter;
    let no = 1;

    for (const user of users) {
        const id = user.id
        const name = user.last_name;
        const firstName = user.first_name;
        const email = user.email;
        const phone = user.phone;
        const isAdmin = user.is_admin == 1;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no}</td>
            <td>${name}</td>
            <td>${firstName}</td>
            <td>${email}</td>
            <td>${phone}</td>
            <td>
                ${id === userIdSession ? '<span data-bs-toggle="tooltip" data-bs-title="Changer ses droits depuis cet espace est impossible">' : ''}
                    <div class="form-check form-switch">
                        <input type="checkbox"
                            class="form-check-input"
                            id="switchUser${id}"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmSwitch${id}"
                            ${isAdmin ? 'checked' : ''}
                            ${id === userIdSession ? 'disabled' : ''}
                            onclick="return false;">
                        <label class="form-check-label" for="switchUser${id}">
                            ${isAdmin ? '<i class="bi bi-person-fill-gear"></i>' : '<i class="bi bi-person-fill"></i>'}
                        </label>
                    </div>
                    ${generateSwitchModal(id, isAdmin)}
                ${id === userIdSession ? '</span>' : ''}
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProfile${id}"><i class="bi bi-pencil"></i></button>
                
                ${id === userIdSession ? '<span data-bs-toggle="tooltip" data-bs-title="Supprimer son propre compte depuis cet espace est impossible">' : ''}
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProfile${id}" ${id === userIdSession ? 'disabled' : ''}>
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                ${id === userIdSession ? '</span>' : ''}

                ${generateDeleteModal(id)}
                ${generateEditModal(id, user)}

            </td>`;
                
        tbody.appendChild(tr);
        no++;
    }
}

function generateSwitchModal(id, isAdmin) {
    return `
    <div class="modal fade" id="confirmSwitch${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
            <div class="modal-header">
                <h5 class="modal-title">Changer les droits</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous ${isAdmin ? "retirer" : "attribuer"} les droits administrateur ?
                <input type="hidden" name="id" value="${id}">
                <input type="hidden" name="new_droits" value="${isAdmin ? 0 : 1}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success">Confirmer</button>
            </div>
            </form>
        </div>
        </div>
    </div>`;
}

function generateDeleteModal(id) {
    return `
    <div class="modal fade" id="deleteProfile${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer un profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cet utilisateur ?
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

function generateEditModal(id, user) {
    return `
    <div class="modal fade" id="editProfile${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
            <div class="modal-header">
                <h5 class="modal-title">Modifier un profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="${id}">
                <div class="mb-3"><label>Nom</label><input name="nom" class="form-control" value="${user.last_name}" /></div>
                <div class="mb-3"><label>Prénom</label><input name="prenom" class="form-control" value="${user.first_name}" /></div>
                <div class="mb-3"><label>Téléphone</label><input name="tel" class="form-control" value="${user.phone}" /></div>
                <div class="mb-3"><label>Email</label><input name="email" class="form-control" value="${user.email}" /></div>
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
