let baseTotal = 0;
let selectedAcc = {};

export function updateTotal() {
    const radios = document.querySelectorAll('.acc-radio');
    const totalSpan = document.getElementById('total');

    if (!totalSpan) return;

    baseTotal = parseFloat(totalSpan.textContent.replace("€", "").trim()) || 0;

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            const stopName = radio.name;
            const price = parseFloat(radio.dataset.price);
            selectedAcc[stopName] = price;
            recalculateTotal();
        });
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

function recalculateTotal() {
    const totalSpan = document.getElementById('total');
    if (!totalSpan) return;

    let total = baseTotal;

    for (let key in selectedAcc) {
        total += selectedAcc[key];
    }

    document.querySelectorAll('.service-item').forEach(item => {
        const qtySpan = item.querySelector('.qty-service');
        const price = parseFloat(qtySpan.dataset.price);
        const qty = parseInt(qtySpan.textContent);
        total += qty * price;
    });

    totalSpan.textContent = total.toFixed(2) + " €";
}

function updateSubtotal(span) {
    const price = parseFloat(span.dataset.price);
    const qty = parseInt(span.textContent);
    const parent = span.closest('.service-item');
    const subDiv = parent.querySelector('.sub');
    subDiv.textContent = (price * qty).toFixed(2) + " €";
}
