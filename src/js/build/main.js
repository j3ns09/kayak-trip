import { loadMap } from "./map/map.js";
import { loadRoadMap } from "./roadMap.js";
import { setDates, submitForm, updateEndDate } from "./form.js";

document.addEventListener('DOMContentLoaded', async function () {
    loadMap();
    loadRoadMap();

    setDates();
    updateEndDate();
    submitForm();
});