export async function loadPromotions() {
    const response = await fetch("/api/promotions/", {
            method: 'GET'
    });
    
    const tbody = document.querySelector('#promotions-list');

    const data = await response.json();
    const discounts = data.discounts;
    const userIdSession = data.waiter;
    let no = 1;

    for (const discount of discounts) {
        const id = discount.id
        const name = discount.name
        const start = discount.start_date;
        const end = discount.end_date;
        const reduction = discount.discount_value;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no}</td>
            <td>${name}</td>
            <td>${start ? start + " - " + end : "Indéfinie"}</td>
            <td>${reduction} %</td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editDiscount${id}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDiscount${id}"><i class="bi bi-trash"></i></button>

                ${generateDeleteModal(id)}
                ${generateEditModal(id, discount)}

            </td>`;

        tbody.appendChild(tr);

        no++;
    }
}

function generateDeleteModal(id) {
    return `
    <div class="modal fade" id="deleteDiscount${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/promotion/delete_promo_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer une promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette promotion ?
                <input type="hidden" name="code" value="${id}">
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

function generateEditModal(id, promotion) {
    return `
    <div class="modal fade" id="editDiscount${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/promotion/edit_promo_process.php">
            <div class="modal-header">
                <h5 class="modal-title">Modifier une promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="code" value="${id}">
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input name="code" class="form-control" value="${promotion.id}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="discount-start" class="form-control" value="${promotion.start_date}" min="${today()}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="discount-end" class="form-control" value="${promotion.end_date}" min="${today()}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Remise (%)</label>
                    <input type="number" name="reduction" class="form-control" value="${promotion.discount_value}" />
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