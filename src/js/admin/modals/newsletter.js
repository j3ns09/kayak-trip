let subsData = [];
let currentPage = 1;
const itemsPerPage = 10;

export async function loadSubscribers() {
    const response = await fetch("/api/subscribers/", {
        method: 'GET'
    });

    const data = await response.json();
    subsData = data.subs;

    renderPage(1);
    renderPagination();
}


function renderPage(page) {
    currentPage = page;
    const tbody = document.querySelector('#subscribersShowing');

    tbody.innerHTML = "";

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const subs = subsData.slice(start, end);

    let no = start + 1;

    for (const sub of subs) {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${no}</td>
            <td>${sub.email}</td>
            <td>
                ${sub.subscribed_at}
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSub${sub.id}">
                    <i class="bi bi-trash3-fill"></i>
                </button>
                ${generateDeleteModal(sub.id)}
            </td>
        `;

        tbody.appendChild(tr);
        no++;
    }
}

function generateDeleteModal(id) {
    return `
    <div class="modal fade" id="deleteSub${id}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/processes/newsletter/delete_sub_process.php">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer un abonné</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer cet abonné ?
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

function renderPagination() {
    const totalPages = Math.ceil(subsData.length / itemsPerPage);
    const pagination = document.getElementById("news-pagination");
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