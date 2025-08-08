import { loadMap } from "./map/map.js";
import { loadRoadMap } from "./roadMap.js";

document.addEventListener('DOMContentLoaded', async function () {
    let map;
    
    loadMap();
    loadRoadMap();
});