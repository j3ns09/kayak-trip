import { loadUsers } from "./modals/users.js";
import { loadStops } from "./modals/stops.js";
import { loadAccommodations } from "./modals/accommodations.js";
import { loadMap } from "./map/map.js";

document.addEventListener('DOMContentLoaded', async function () {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            links.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    await loadUsers();
    await loadAccommodations();
    
    let map;
    await loadStops();
    
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    
    loadMap();
});