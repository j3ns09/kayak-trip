import { loadMap } from "./map/map.js";
import { loadRoadMap } from "./roadMap.js";

document.addEventListener('DOMContentLoaded', async function () {
    loadMap();
    loadRoadMap();
});