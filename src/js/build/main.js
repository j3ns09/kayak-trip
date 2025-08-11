import { loadMap } from "./map/map.js";
import { loadRoadMap } from "./roadMap.js";
import { submitForm } from "./form.js";

document.addEventListener('DOMContentLoaded', async function () {
    loadMap();
    loadRoadMap();
    submitForm();
});