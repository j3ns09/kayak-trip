const checkoutBtn = document.getElementById('checkout-btn');

export function submitForm() {
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            submitCheckout();
        });
    }
}

function submitCheckout() {
    const accommodations = {};
    document.querySelectorAll('.acc-radio:checked').forEach(radio => {
        const stopId = radio.name.replace('acc-', '');
        const accommodationId = radio.id.replace('radio-', '');
        accommodations[stopId] = parseInt(accommodationId);
    });

    const quantities = {};
    document.querySelectorAll('.qty-service').forEach(span => {
        const parent = span.closest('[id^="sub-"]');
        const serviceId = parent?.id.replace('sub-', '').trim();
        const qty = parseInt(span.textContent.trim());

        if (!isNaN(serviceId) && !isNaN(qty)) {
            quantities[serviceId] = qty;
        }
    });

    const discountInput = document.getElementById('discount-code');
    const discountCode = discountInput ? discountInput.value.trim() : null;

    const totalSpan = document.getElementById('total');
    let total = parseFloat(totalSpan.textContent.replace('€', '').trim());
    if (isNaN(total)) total = 0;

    const startDateElem = document.getElementById('start-date');
    const endDateElem = document.getElementById('end-date');
    const durationElem = document.getElementById('duration');
    const personCountElem = document.getElementById('person-count');

    const startDate = startDateElem ? startDateElem.textContent.trim() : null;
    const endDate = endDateElem ? endDateElem.textContent.trim() : null;
    const duration = durationElem ? parseInt(durationElem.textContent.trim()) : null;
    const personCount = personCountElem ? parseInt(personCountElem.textContent.trim()) : null;

    const data = {
        accommodations,
        quantities,
        discountCode,
        total,
        desiredTime: {
            duration,
            dates: [startDate, endDate]
        },
        personCount
    };

    fetch('/api/checkout/', {
        method: 'POST',
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.ok) {
            window.location.href = '/confirmation.php';
        } else {
            alert(result.message || "Une erreur est survenue lors du traitement de la commande.");
        }
    })
    .catch(err => {
        console.error("Erreur de requête :", err);
        alert("Erreur de communication avec le serveur.");
    });
}