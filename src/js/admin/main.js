import { renderToasts } from "../sugar/dashboard.js";

import { loadUsers } from "./modals/users.js";
import { loadStops } from "./modals/stops.js";
import { loadAccommodations } from "./modals/accommodations.js";
import { loadServices } from "./modals/services.js";
import { loadDiscounts, today } from "./modals/promotions.js";
import { loadPacks } from "./modals/packs.js";
import { loadMap } from "./map/map.js";

document.addEventListener('DOMContentLoaded', async function () {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            links.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    renderToasts();
    
    await loadUsers();
    await loadAccommodations();
    await loadServices();
    await loadDiscounts();
    await loadPacks();

    let map;
    await loadStops();
    
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    
    loadMap();

    setMinDate();
});

function setMinDate() {
    const discountStart = document.getElementById('discount-start');
    const discountEnd = document.getElementById('discount-end');

    discountStart.setAttribute('min', today());
    discountEnd.setAttribute('min', today());
}