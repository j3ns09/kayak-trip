let bookingsData = [];
let currentPage = 1;
const itemsPerPage = 10;

export async function loadBookings() {
    const response = await fetch("/api/bookings/", {
        method: 'GET'
    });

    const data = await response.json();
    bookingsData = data.bookings;

    renderPage(1);
    renderPagination();
}


function renderPage(page) {
    currentPage = page;
    const tbody = document.querySelector('#ordersShowing');

    tbody.innerHTML = "";

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const bookings = bookingsData.slice(start, end);

    let no = start + 1;

    for (const booking of bookings) {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${no}</td>
            <td>${booking.first_name} ${booking.last_name}</td>
            <td>${booking.start_date}</td>
            <td>${booking.end_date}</td>
            <td>${booking.created_at}</td>
            <td>${booking.total_price} €</td>
            <td>${booking.is_paid ? "Oui" : "Non"}</td>
            <td>
                ${booking.promotion_code_used ? "Oui" : "Non"}
            </td>
        `;

        tbody.appendChild(tr);
        no++;
    }
}

function renderPagination() {
    const totalPages = Math.ceil(bookingsData.length / itemsPerPage);
    const pagination = document.getElementById("orders-pagination");
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