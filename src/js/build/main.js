import { loadMap } from "./map/map.js";
import { loadRoadMap } from "./roadMap.js";
import { renderServices } from "./services/services.js";
import { setDates, submitForm, updateEndDate } from "./form.js";

document.addEventListener('DOMContentLoaded', async function () {
    loadMap();
    loadRoadMap();

    await renderServices();
    setDates();
    updateEndDate();
    submitForm();
});