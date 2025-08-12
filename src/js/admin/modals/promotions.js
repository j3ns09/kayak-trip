export async function loadDiscounts() {
    const response = await fetch("/api/discounts/", {
            method: 'GET'
    });
    
    const tbody = document.querySelector('#discountsShowing');

    const data = await response.json();
    const discounts = data.discounts;
    const userIdSession = data.waiter;
    let no = 1;

    for (const discount of discounts) {
        const code = discount.code
        const start = discount.valid_from;
        const end = discount.valid_to;
        const description = discount.description;
        const reduction = discount.discount_value;
        const firstTimeOnly = discount.first_time_only;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no}</td>
            <td>${code}</td>
            <td>${start ? start + " - " + end : "Indéfinie"}</td>
            <td>${description}</td>
            <td>${reduction} %</td>
            <td>${firstTimeOnly == 1 ? "Oui" : "Non"}</td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editDiscount${code}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDiscount${code}"><i class="bi bi-trash"></i></button>

                ${generateDeleteModal(code)}
                ${generateEditModal(code, discount)}

            </td>`;

        tbody.appendChild(tr);

        no++;
    }
}

function generateDeleteModal(code) {
    return `
    <div class="modal fade" id="deleteDiscount${code}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/discounts/delete_discount_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer une promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette promotion ?
                <input type="hidden" name="code" value="${code}">
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

function generateEditModal(code, discount) {
    return `
    <div class="modal fade" id="editDiscount${code}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/discounts/edit_discount_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Modifier une promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="code" value="${code}">
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input name="code" class="form-control" value="${discount.code}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="discount-start" class="form-control" value="${discount.valid_from}" min="${today()}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="discount-end" class="form-control" value="${discount.valid_to}" min="${today()}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" value="${discount.description}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Remise (%)</label>
                    <input type="number" name="reduction" class="form-control" value="${discount.discount_value}" />
                </div>
                
                <div class="mb-3">
                    <label class="form-check-label">Utilisation unique</label>
                    <input type="checkbox" name="discount-use" class="form-check-input" value="${discount.first_time_only == 1 ? 1 : 0}" ${discount.first_time_only == 1 ? "checked" : ""}/>
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

export function today() {
    const date = new Date();

    let day = date.getDate();
    let month = date.getMonth() + 1;
    if (month.toString().length < 2) {
        month = '0' + month;
    }
    let year = date.getFullYear();
    let currentDate = `${year}-${month}-${day}`;

    return currentDate;
}