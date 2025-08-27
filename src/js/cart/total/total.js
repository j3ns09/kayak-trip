const radios = document.querySelectorAll('.acc-radio');
const totalSpan = document.getElementById('total');
const discountValue = document.getElementById('discount-value');

let discountPercent = 0;
let selectedRooms = {};

const baseTotal = parseFloat(totalSpan.textContent.replace("€", "").trim()) || 0;
let selectedAcc = {};

export function updateTotal() {
    if (!totalSpan) return;

    document.querySelectorAll('input[type="checkbox"][name^="room-"]').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const accId = checkbox.name.replace('[]', '').split('-')[1];
            const roomId = checkbox.value;
            const price = parseFloat(checkbox.dataset.price);

            if (!selectedRooms[accId]) {
                selectedRooms[accId] = [];
            }

            if (checkbox.checked) {
                selectedRooms[accId].push({ id: roomId, price });
            } else {
                selectedRooms[accId] = selectedRooms[accId].filter(r => r.id !== roomId);
                if (selectedRooms[accId].length === 0) {
                    delete selectedRooms[accId];
                }
            }

            recalculateTotal();
        });
    });

    discountValue.addEventListener('change', () => {
        const reduction = parseFloat(discountValue.value);
     
        if (!isNaN(reduction) && reduction > 0) {
            discountPercent = reduction;
        } else {
            discountPercent = 0;
        }

        recalculateTotal();
    });
}

export function updateQuantities() {
    const quantityButtonsMin = document.querySelectorAll('.min-btn');
    const quantityButtonsPlus = document.querySelectorAll('.pls-btn');

    quantityButtonsMin.forEach(btn => {
        btn.addEventListener('click', () => {
            const span = btn.nextElementSibling;
            let qty = parseInt(span.textContent);
            if (qty > 0) {
                qty--;
                span.textContent = qty;
                updateSubtotal(span);
                recalculateTotal();
            }
        });
    });

    quantityButtonsPlus.forEach(btn => {
        btn.addEventListener('click', () => {
            const span = btn.previousElementSibling;
            let qty = parseInt(span.textContent);
            qty++;
            span.textContent = qty;
            updateSubtotal(span);
            recalculateTotal();
        });
    });
}

export function recalculateTotal() {
    const totalSpan = document.getElementById('total');
    if (!totalSpan) return;

    let total = baseTotal;

    for (let accId in selectedRooms) {
        selectedRooms[accId].forEach(room => {
            total += room.price;
        });
    }

    for (let key in selectedAcc) {
        total += selectedAcc[key];
    }

    document.querySelectorAll('.service-item').forEach(item => {
        const qtySpan = item.querySelector('.qty-service');
        const price = parseFloat(qtySpan.dataset.price);
        const qty = parseInt(qtySpan.textContent);
        total += qty * price;
    });

    if (discountPercent > 0) {
        total = total - (total * discountPercent / 100);
    }

    totalSpan.textContent = total.toFixed(2) + " €";
}

function updateSubtotal(span) {
    const price = parseFloat(span.dataset.price);
    const qty = parseInt(span.textContent);
    const parent = span.closest('.service-item');
    const subDiv = parent.querySelector('.sub');
    subDiv.textContent = (price * qty).toFixed(2) + " €";
}
